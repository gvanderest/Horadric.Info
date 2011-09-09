<?php
/**
 * Forum View and Decorator
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package forum
 */
class Forum_View extends Horadric_View
{
    /**
     * Display a vote button
     * @param object $post
     * @return string html
     */
    public function display_small_post_vote($post, $options = array())
    {
        return '
            <span class="vote_forum_posts_' . $post->id . ' vote small">
                <a class="up' . ($post->user_vote == 1 ? ' active' : '') . '" href="/forums/vote-post-up/' . $post->id . '">Vote Up</a>
                <a class="down' . ($post->user_vote == -1 ? ' active' : '') . '" href="/forums/vote-post-down/' . $post->id . '">Vote Down</a>
                <a class="clear" href="/forums/vote-post-clear/' . $post->id . '">Clear Vote</a>
                <span class="score' . ($post->user_vote != 0 ? ' active' : '') . '">' . (int)$post->score .'</span>
            </span>
        ';
    }

    /**
     * Display a vote button
     * @param object $post
     * @return string html
     */
    public function display_post_vote($post, $options = array())
    {
        return '
            <span class="vote_forum_posts_' . $post->id . ' vote">
                <a class="up' . ($post->user_vote == 1 ? ' active' : '') . '" href="/forums/vote-post-up/' . $post->id . '">Vote Up</a>
                <span class="score' . ($post->user_vote != 0 ? ' active' : '') . '">' . (int)$post->score .'</span>
                <a class="down' . ($post->user_vote == -1 ? ' active' : '') . '" href="/forums/vote-post-down/' . $post->id . '">Vote Down</a>
                <a class="clear" href="/forums/vote-post-clear/' . $post->id . '">Clear Vote</a>
            </span>
        ';
    }

    /**
     * Display the pagination for a page
     * @param int $page
     * @param int $pages
     */
    public function display_pagination($page, $pages)
    {
        $output = '';
        $output .= '<span class="paginator">Page: <ul>';
        for ($x = 1; $x <= $pages; $x++)
        {
            $classes = array();
            if ($x == $page) { $classes[] = 'active'; }

            $output .= '<li class="' . implode(' ', $classes) . '"><a href="?p=' . $x . '">' . $x . '</a></li>';
        }
        $output .= '</ul></span>';
        return $output;
    }

    /**
     * Parse a post
     * @param string $body
     * @return string output
     */
    public function parse_post($body)
    {
        $output = trim($body);

        $output = htmlentities($output);

        $output = preg_replace('/\[url\=\&quot;(.+?)\&quot;\](.+?)\[\/url\]/sim', '<a href="${1}" target="_blank">${2}</a>', $output);
        
        $output = preg_replace('/\[b\](.+?)\[\/b\]/sim', '<b>${1}</b>', $output);
        $output = preg_replace('/\[em\](.+?)\[\/em\]/sim', '<em>${1}</em>', $output);
        $output = preg_replace('/\v?\[ul\](.+?)\[\/ul\]\s?\v?/sim', '<ul>${1}</ul>', $output);
        $output = preg_replace('/\[li\](.+?)\[\/li\]\s?\v?/sim', '<li>${1}</li>', $output);
        $output = preg_replace('/\[excerpt\](.+?)\[\/excerpt\]\s?\v?/sim', '${1}', $output);
        $output = preg_replace('/\[quote\](.+?)\[\/quote\]/sim', '<q>${1}</q>', $output);

        // always downgrade headings by one level to not screw with SEO
        $output = preg_replace('/\[h1\](.+?)\[\/h1\]\s?\v?/sim', '<h2>${1}</h2>', $output);
        $output = preg_replace('/\[h2\](.+?)\[\/h2\]\s?\v?/sim', '<h3>${1}</h3>', $output);
        $output = preg_replace('/\[h3\](.+?)\[\/h3\]\s?\v?/sim', '<h4>${1}</h4>', $output);

        // TODO rewrite this to not allow exploiting, so obvious
        if (preg_match_all("/\[blizzard name=\&quot;(.+?)\&quot; source=\&quot;(.+?)\&quot;\](.+?)\[\/blizzard\]\s*/sim", $output, $matches))
        {
            foreach ($matches[1] as $index => $match)
            {
                $match_name = $match;
                $match_url = $matches[2][$index];
                $match_body = $matches[3][$index];

                $output = str_replace($matches[0][$index], '<blockquote class="blizzard"><div class="quote-byline">Originally Posted by <strong>' . $match_name . '</strong> (<a href="' . $match_url . '" target="_blank">Source</a>)</div>' . trim($match_body) . '</blockquote>', $output);
            }
        }

        $output = preg_replace("/\[email\](.+?)\[\/email\]/sim", '<a href="mailto:${1}">${1}</a>', $output);
        $output = preg_replace('/\[youtube\](.+?)\[\/youtube\]\s?\v?/sim', '<div class="youtube"><iframe style="margin: 15px 0 15px 0" width="560" height="345" src="http://www.youtube.com/embed/${1}" frameborder="0" allowfullscreen></iframe></div>', $output);

        // if you wanted to make examples, you could!
        $output = str_replace('\[', '[', $output);
        $output = str_replace('\]', ']', $output);

        $output = nl2br($output);

        return $output;
    }
}
