export default {
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    theme: {
        extend: {
            colors: {
                persi: {
                    cream: "#F4F1E6",
                    green: "#0F4B4B",
                    orange: "#D86826",
                    gold: "#E2B04A",
                },
            },
            boxShadow: {
                "persi-panel": "0 10px 30px rgba(0,0,0,0.15)",
            },
            borderRadius: {
                "persi-xl": "26px",
            },
        },
    },
    plugins: [],
};
