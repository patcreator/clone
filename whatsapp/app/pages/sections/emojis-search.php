 <script>

$(document).ready(function(){



    $("#filterBox").keyup(function(){



        // Retrieve the input field text and reset the count to zero

        var filter = $(this).val(), count = 0;



        // Loop through the list

        $(".filter-list li").each(function(){

                            

                                // Check if the element has a 'data-keywords' attribute. If so, apply its value to the 'keywords' variable.

                                var keywordsAttr = $(this).attr("data-keywords");

                                var keywords = "";

                                if (typeof keywordsAttr !== typeof undefined && keywordsAttr !== false) {

                                        keywords = keywordsAttr;

                                }



            // If the list item doesn't contain the phrase, fade it out

            if ($(this).text().search(new RegExp(filter, "i")) < 0 && keywords.search(new RegExp(filter, "i")) < 0) {

                $(this).fadeOut();



            // Display the item if the phrase matches, then increase the count by 1

            } else {

                $(this).show();

                count++;

            }

        });

        

            // Update the count

            var resultCount = count;

            $("#resultCount").text("Results: "+count);

        

    });

});

</script>
<div class="filter">

<label for="filterBox"><i class="fa fa-search"></i> Filter by: 

    <input type="search" name="filterBox" id="filterBox" class="filter-box" placeholder="e.g. happy" aria-label="e.g. happy">

    <span class="filter-count" id="resultCount"></span>

</label>

</div>