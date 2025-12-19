<footer class="footer mt-auto xl:ps-[15rem] font-normal font-inter bg-white leading-normal !text-[0.875rem] shadow-[0_0_0.4rem_rgba(0,0,0,0.1)] dark:bg-bodybg py-4 text-center">
    <div class="container">
        <span class="text-gray dark:text-defaulttextcolor/50">
            Copyright © <span id="year"></span> <a href="javascript:void(0);" class="text-defaulttextcolor font-semibold dark:text-defaulttextcolor">{{env('APP_NAME')}}</a>. All rights reserved.
        </span>
    </div>
</footer>

<script>
    // Automatically set the current year
    document.getElementById('year').textContent = new Date().getFullYear();
</script>
