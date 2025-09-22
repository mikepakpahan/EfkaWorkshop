<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $__env->yieldContent('title', 'EfkaWorkshop'); ?> </title>
    <link rel="stylesheet" href='/EfkaWorkshop/resources/css/customer.css'>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/EfkaWorkshop/assets/libs/sweetalert2/sweetalert2.min.css">
    <script src="/EfkaWorkshop/assets/libs/sweetalert2/sweetalert2.all.min.js"></script>
</head>

<body>
    <svg style="position: absolute; width: 0; height: 0">
        <filter id="glass-distortion">
            <feTurbulence type="fractalNoise" baseFrequency="0.01 0.04" numOctaves="1" result="turbulence">
            </feTurbulence>
            <feGaussianBlur in="turbulence" stdDeviation="3" result="softMap"></feGaussianBlur>
            <feDisplacementMap in="SourceGraphic" in2="softMap" scale="50" xChannelSelector="R"
                yChannelSelector="G"></feDisplacementMap>
        </filter>
    </svg>
    <?php echo $__env->make('layout.partials.customer.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo $__env->make('layout.partials.customer.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
        function goHome() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }

        const hamburgerBtn = document.getElementById('hamburger-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (hamburgerBtn && mobileMenu) {
            hamburgerBtn.addEventListener('click', function() {
                this.classList.toggle('is-active');
                mobileMenu.classList.toggle('is-open');
            });
        }

        const navToggle = document.getElementById("navToggle");
        const mobileNav = document.getElementById("mobileNav");
        navToggle.addEventListener("click", function() {
            if (mobileNav.style.display === "flex") {
                mobileNav.style.display = "none";
            } else {
                mobileNav.style.display = "flex";
            }
        });
        window.addEventListener("click", function(e) {
            if (
                mobileNav.style.display === "flex" &&
                !mobileNav.contains(e.target) &&
                !navToggle.contains(e.target)
            ) {
                mobileNav.style.display = "none";
            }
        });
    </script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\EfkaWorkshop\resources\views/layout/customer.blade.php ENDPATH**/ ?>