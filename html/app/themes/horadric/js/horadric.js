/**
 * Horadric.Info Javascript
 */

$(document).ready(function(){
    
    // vote buttons
    $('.vote .up, .vote .down').html('')
    $('.vote .clear').hide();

    $('.vote .up, .vote .down, .vote .clear').each(function(){
        $(this).attr('href', $(this).attr('href') + '?json');  
    });

    $('.vote .up, .vote .down, .vote .clear').click(function(e){
    
        e.preventDefault();
        
        if ($(this).hasClass('active'))
        {
            // send out the ajax for the clear one, its response will give a score
            $(this).parent('.vote').children('.clear').click();
            return;
        }

        // make the call, coach
        $.ajax({
            url: $(this).attr('href'),
            data: {},
            success: function(response){
                if (!response.success)
                {
                    if (response.error)
                    {
                        alert(response.error);
                    } else {
                        alert('Your vote attempt has failed due to server issues. Please try again later.');
                    }
                    return;
                }

                // remove highlighting
                var vote_id = '.vote_' + response.entity_type + '_' + response.entity_id;
                $(vote_id).find('.up,.down,.score').removeClass('active');

                // highlight the appropriate vote button
                if (response.voted == 1 || response.voted == -1)
                {
                    $(vote_id + ' ' + (response.voted == 1 ? '.up' : '.down')).addClass('active');
                    $(vote_id + ' .score').addClass('active');
                }

                // update score
                $(vote_id + ' .score').html(response.score);
            },
            dataType: 'json'
        });
    });

});
