
{{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>--}}
{{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>--}}

<script>
  $(document).ready(function () {
    $('#nav-search').val('');
    let currentLI = -1; // start from -1 because no item is highlighted initially
    const keydown = 38;
    const keyup = 40;
    const keyEnter = 13;

    function showDropdown() {
        $('.card-admin-search').show();
    }

    function hideDropdown() {
        $('.card-admin-search').hide();
        currentLI = -1;
    }

    $("#nav-search").on("keyup", function (e) {
        let keycode = e.keyCode;
        const listContainer = $('#nav-search-listing');
        const listItems = listContainer.find('li');

        // handle arrow keys and enter
        if (keycode === keydown || keycode === keyup || keycode === keyEnter) {
            if (listItems.length === 0) return;
            listItems.removeClass('highlight');

            if (keycode === keydown) {
                currentLI = currentLI > 0 ? currentLI - 1 : 0;
            } else if (keycode === keyup) {
                currentLI = currentLI < listItems.length - 1 ? currentLI + 1 : listItems.length - 1;
            } else if (keycode === keyEnter) {
                const url = listItems.eq(currentLI).find('a').attr('href');
                if (url) window.location.href = url;
            }

            if (currentLI >= 0) listItems.eq(currentLI).addClass('highlight');
            return;
        }

        // normal typing search
        let linksArr = {};
        $('.sidebar-menu li a').each(function () {
            let href = $(this).data('href');
            if (href && href !== '#') {
                let name = $(this).text().toLowerCase();
                linksArr[name] = href;
            }
        });

        const searchQuery = $(this).val().toLowerCase();
        listContainer.empty();

        if (searchQuery.length > 0) {
            for (let name in linksArr) {
                if (name.indexOf(searchQuery) !== -1) {
                    listContainer.append('<li class="nav-listing list-group-item"><a href="'+linksArr[name]+'">'+name+'</a></li>');
                }
            }
            showDropdown();
        } else {
            hideDropdown();
        }

        currentLI = -1;
    });

    // hide dropdown when clicking outside
    $(document).click(function (evt) {
        if (!$(evt.target).closest('.search-form').length) {
            hideDropdown();
        }
    });

    // focusin shows dropdown
    $('#nav-search').focusin(showDropdown);

    // ctrl + Q to focus
    window.addEventListener("keydown", function (e) {
        if (e.ctrlKey && e.keyCode === 81) {
            e.preventDefault();
            $('#nav-search').focus();
        }
    });
});


</script>
