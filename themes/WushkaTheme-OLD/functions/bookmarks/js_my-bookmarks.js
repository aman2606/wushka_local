jQuery(document).ready(function ($) {
    /* -------------------------------
     * 		User Bookmarks JS
     * ------------------------------- */
    $(document).on('click', '[id^="btn-bookmark-"].btn-bookmark', function (e) {
        e.preventDefault();

        var this_bookmark_btn = $(this);
        var book_id = $(this).attr('id').replace('btn-bookmark-', '').trim();
        var text_name;

        console.log('Bookmark Button Pressed');

        console.log('Book ID to Bookmark: ' + book_id);
        //Determine If Book is Already Collected\\
        if (this_bookmark_btn.hasClass('marked')) {
            console.log('This Book needs to be unMarked.');
            text_name = 'Remove';
            this_bookmark_btn.find('span.bookmark-label').text('Bookmark');
            this_bookmark_btn.find('i.bookmark-glyph').removeClass('starred');
            if (this_bookmark_btn.hasClass('favourite')) {
                this_bookmark_btn.find('span.bookmark-label').text('Favourite');
                this_bookmark_btn.find('i.bookmark-glyph').removeClass('starred');
            }
        } else {
            console.log('Bookmark This Book');
            text_name = 'Bookmark';
            this_bookmark_btn.find('span.bookmark-label').text('Remove');
            this_bookmark_btn.find('i.bookmark-glyph').addClass('starred');
            if (this_bookmark_btn.hasClass('favourite')) {
                text_name = 'Favourite';
            }
        }
        var b_markpage = false;
        if (this_bookmark_btn.parents('.wushka-bookmarks').find('img.book-thumb').length > 0 && text_name == 'Remove') {
            this_bookmark_btn.parents('.wushka-bookmarks').fadeTo(200, 0.5);
            b_markpage = true;
        }

        this_bookmark_btn.toggleClass('marked');

        $.ajax({
            url: temp_fle_drctry + 'ajax_my-bookmarks.php',
            type: 'post',
            dataType: 'json',
            data: {
                'ajax_function': 'toggle_book',
                'book_id': book_id
            },
            error: function () {
                console.log('Ajax Error: Ajax Failed to run');
            },
            success: function (ajax_return) {
                console.log('Bookmark Finished: ' + ajax_return);
                if (ajax_return == 0) {
                    console.log('Failed. There was an error Marking this Book!');
                    this_bookmark_btn.find('span.bookmark-label').text(text_name);
                    this_bookmark_btn.toggleClass('marked');
                    if (b_markpage == true && text_name == 'Remove') {
                        this_bookmark_btn.parents('.wushka-bookmarks').fadeTo(200, 1);
                    }

                } else if (ajax_return == 1) {
                    console.log('Successfully Bookmarked!');
                    if (b_markpage == true) {
                        $('#book-' + book_id).fadeTo(200, 0, function () {
                            $('#book-' + book_id).remove();
                            checkIfEmpty();
                        });
                    }
                }
                return true;
            }
        });
    });

    /* ----- Remove All Bookmarks ----- */
    $('#remove-bookmarks').on('click', function () {
        var aBooks = $('[id^="btn-bookmark-"].btn-bookmark');
        if (aBooks !== null && aBooks.length > 0) {
            $.each(aBooks, function (idx, eBook) {
                eBook.click();
            });
        }

        return true;
    });

    function checkIfEmpty() {
        var aBooks = $('[id^="btn-bookmark-"].btn-bookmark');
        if (aBooks == null || aBooks.length <= 0) {
            $('#bookmarks-panel').find('.panel-body').empty().append('<div class="bookmarks error-msg">' +
                '<p>It appears you don\'t have any bookmarks. Go to our Reading Boxes and select a reader to bookmark a reader.</p>' +
                '</div>'
            );
        }
    }

    /* ----- Sort Bookmarks By Date ----- */
    $('#bookmarks-panel').find('.bookmark-group').find('.btn-filter').on('click', function () {
        //Prevent Selected Filter from running again
        if ($(this).hasClass('selected')) {
            return false;
        }

        //Toggle Button to new Selection
        $('.bookmark-group').find('.btn-filter').removeClass('selected');
        $(this).addClass('selected');

        var o_panel = $('#bookmarks-panel');

        //Retrieve all Bookmarked Items into array
        var a_books = [];
        var o_books = o_panel.find('.wushka-bookmarks.book-wrap').clone();

        o_books.each(function (index) {
            a_books.push(o_books[index]);
        });

        if (a_books.length > 0) {
            //Reverse order of books
            a_books.reverse();
            //Remove existing book html from page and add sorted ones back in
            var o_wrap = o_panel.find('.bookmark-wrap');
            o_wrap.empty().append(a_books);
        }

        o_books = null;
        a_books = null;

        return true;
    });
});