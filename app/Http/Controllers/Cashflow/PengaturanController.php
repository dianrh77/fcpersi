<?php

namespace App\Http\Controllers\Cashflow;

use App\Http\Controllers\Controller;
use App\Models\CoaAccount;
use App\Models\Member;
use App\Models\MemberClass;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $coaAccounts = CoaAccount::with('parent')
            ->orderBy('type')
            ->orderBy('code')
            ->get();

        $coaParents = CoaAccount::orderBy('code')->get();

        $memberClassOptions = MemberClass::orderBy('code')->get();

        $memberClasses = MemberClass::orderBy('code')->get();

        $members = Member::with('memberClass')
            ->orderBy('name')
            ->get();

        $nextPersiCode = $this->generateNextPersiCode();

        return view('pages.persi.pengaturan.index', compact(
            'coaAccounts',
            'coaParents',
            'memberClasses',
            'members',
            'memberClassOptions',
            'nextPersiCode'
        ));
    }

    public function storeCoa(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:coa_accounts,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $parent = null;
        $type = 'income';
        if (!empty($data['parent_id'])) {
            $parent = CoaAccount::findOrFail($data['parent_id']);
            $type = $parent->type;
        }

        $level = $parent ? ((int) $parent->level + 1) : 1;

        $code = $this->generateCoaCode($parent);

        CoaAccount::create([
            'code' => $code,
            'name' => $data['name'],
            'type' => $type,
            'parent_id' => $data['parent_id'] ?? null,
            'level' => $level,
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        return redirect()
            ->route('persi.pengaturan', ['tab' => 'coa'])
            ->with('success', 'COA berhasil ditambahkan.');
    }

    private function generateCoaCode(?CoaAccount $parent): string
    {
        if ($parent) {
            $prefix = $parent->code . '.';
            $last = CoaAccount::where('parent_id', $parent->id)
                ->orderBy('code', 'desc')
                ->value('code');

            $next = 1;
            if ($last && str_starts_with($last, $prefix)) {
                $tail = substr($last, strlen($prefix));
                if (is_numeric($tail)) {
                    $next = (int) $tail + 1;
                }
            }

            return $prefix . $next;
        }

        $lastTop = CoaAccount::whereNull('parent_id')
            ->orderBy('code', 'desc')
            ->value('code');

        $nextTop = 1;
        if ($lastTop && is_numeric($lastTop)) {
            $nextTop = (int) $lastTop + 1;
        }

        return (string) $nextTop;
    }

    public function editCoa(CoaAccount $coa)
    {
        $coaParents = CoaAccount::where('id', '!=', $coa->id)
            ->orderBy('code')
            ->get();

        return view('pages.persi.pengaturan.coa-edit', compact('coa', 'coaParents'));
    }

    public function updateCoa(Request $request, CoaAccount $coa)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coa_accounts,code,' . $coa->id],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:income,expense'],
            'parent_id' => ['nullable', 'exists:coa_accounts,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (!empty($data['parent_id']) && (int) $data['parent_id'] === (int) $coa->id) {
            return back()->withErrors(['parent_id' => 'Parent tidak boleh diri sendiri.'])->withInput();
        }

        $parent = null;
        if (!empty($data['parent_id'])) {
            $parent = CoaAccount::findOrFail($data['parent_id']);
            if ($parent->type !== $data['type']) {
                return back()->withErrors(['parent_id' => 'Parent harus satu tipe (income/expense).'])->withInput();
            }
        }

        $level = $parent ? ((int) $parent->level + 1) : 1;

        $coa->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'type' => $data['type'],
            'parent_id' => $data['parent_id'] ?? null,
            'level' => $level,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return redirect()
            ->route('persi.pengaturan', ['tab' => 'coa'])
            ->with('success', 'COA berhasil diperbarui.');
    }

    public function toggleCoa(CoaAccount $coa)
    {
        $coa->update(['is_active' => !$coa->is_active]);

        return redirect()
            ->route('persi.pengaturan', ['tab' => 'coa'])
            ->with('success', 'Status COA berhasil diperbarui.');
    }

    public function storeMemberClass(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:member_classes,code'],
            'name' => ['nullable', 'string', 'max:100'],
            'default_dues_amount' => ['required', 'numeric', 'min:0'],
        ]);

        MemberClass::create([
            'code' => $data['code'],
            'name' => $data['name'] ?? null,
            'default_dues_amount' => (int) $data['default_dues_amount'],
        ]);

        return redirect()
            ->route('persi.pengaturan', ['tab' => 'kelas'])
            ->with('success', 'Kelas RS berhasil ditambahkan.');
    }

    public function editMemberClass(MemberClass $memberClass)
    {
        return view('pages.persi.pengaturan.member-class-edit', compact('memberClass'));
    }

    public function updateMemberClass(Request $request, MemberClass $memberClass)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:member_classes,code,' . $memberClass->id],
            'name' => ['nullable', 'string', 'max:100'],
            'default_dues_amount' => ['required', 'numeric', 'min:0'],
        ]);

        $memberClass->update([
            'code' => $data['code'],
            'name' => $data['name'] ?? null,
            'default_dues_amount' => (int) $data['default_dues_amount'],
        ]);

        return redirect()
            ->route('persi.pengaturan', ['tab' => 'kelas'])
            ->with('success', 'Kelas RS berhasil diperbarui.');
    }

    public function destroyMemberClass(MemberClass $memberClass)
    {
        if ($memberClass->members()->exists()) {
            return back()->withErrors(['member_class' => 'Tidak bisa menghapus: masih ada anggota pada kelas ini.']);
        }

        $memberClass->delete();

        return redirect()
            ->route('persi.pengaturan', ['tab' => 'kelas'])
            ->with('success', 'Kelas RS berhasil dihapus.');
    }

    public function storeMember(Request $request)
    {
        $data = $request->validate([
            'persi_code' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'member_class_id' => ['nullable', 'exists:member_classes,id'],
            'dues_amount' => ['nullable', 'numeric', 'min:0'],
            'address' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'pic_whatsapp' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $persiCode = $data['persi_code'] ?? null;
        if (empty($persiCode)) {
            $persiCode = $this->generateNextPersiCode();
        }

        if (Member::where('persi_code', $persiCode)->exists()) {
            return back()->withErrors(['persi_code' => 'ID PERSI sudah digunakan.'])->withInput();
        }

        Member::create([
            'persi_code' => $persiCode,
            'name' => $data['name'],
            'member_class_id' => $data['member_class_id'] ?? null,
            'dues_amount' => (int) ($data['dues_amount'] ?? 0),
            'address' => $data['address'] ?? null,
            'email' => $data['email'] ?? null,
            'pic_whatsapp' => $data['pic_whatsapp'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        return redirect()
            ->route('persi.pengaturan', ['tab' => 'anggota'])
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    private function generateNextPersiCode(): string
    {
        $prefix = 'PJTG.';
        $max = 0;

        $codes = Member::where('persi_code', 'like', $prefix . '%')
            ->pluck('persi_code');

        foreach ($codes as $code) {
            if (!str_starts_with($code, $prefix)) {
                continue;
            }
            $num = substr($code, strlen($prefix));
            if (is_numeric($num)) {
                $max = max($max, (int) $num);
            }
        }

        $next = $max + 1;
        $width = max(2, strlen((string) $max));

        return $prefix . str_pad((string) $next, $width, '0', STR_PAD_LEFT);
    }

    public function editMember(Member $member)
    {
        $memberClasses = MemberClass::orderBy('code')->get();

        return view('pages.persi.pengaturan.member-edit', compact('member', 'memberClasses'));
    }

    public function updateMember(Request $request, Member $member)
    {
        $data = $request->validate([
            'persi_code' => ['required', 'string', 'max:50', 'unique:members,persi_code,' . $member->id],
            'name' => ['required', 'string', 'max:255'],
            'member_class_id' => ['nullable', 'exists:member_classes,id'],
            'dues_amount' => ['nullable', 'numeric', 'min:0'],
            'address' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'pic_whatsapp' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $member->update([
            'persi_code' => $data['persi_code'],
            'name' => $data['name'],
            'member_class_id' => $data['member_class_id'] ?? null,
            'dues_amount' => (int) ($data['dues_amount'] ?? 0),
            'address' => $data['address'] ?? null,
            'email' => $data['email'] ?? null,
            'pic_whatsapp' => $data['pic_whatsapp'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return redirect()
            ->route('persi.pengaturan', ['tab' => 'anggota'])
            ->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroyMember(Member $member)
    {
        $member->delete();

        return redirect()
            ->route('persi.pengaturan', ['tab' => 'anggota'])
            ->with('success', 'Anggota berhasil dihapus.');
    }
}
