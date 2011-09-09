<?php
/**
 * Horadric Blizzard Diablo 3 Scraper
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package horadric
 */
class Horadric_Scraper_Blizz
{
    public $source;

    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * Fetch and prep the basic scrapes.. From an RSS listing, for example
     * @return array of scrapes with applicable data
     */
    public function get_basic_scrapes()
    {
        $raw_html = curl_get('http://us.battle.net/d3/en/forum/blizztracker/');

        $results = array();

        if (preg_match_all('/<div class="desc">(.+?)<\/div>/sim', $raw_html, $raw_matches))
        {
            foreach ($raw_matches[1] as $raw_match)
            {
                $scrape = new stdClass;
                $scrape->scraped = 0;
                $scrape->source_id = $this->source->id;

                // get the title and url
                $scrape->title = NULL;
                $scrape->url = NULL;
                $scrape->hash = NULL;

                if (preg_match('/<a href="..\/topic\/(.+?)">(.+?)<\/a>/sim', $raw_match, $raw_match))
                {
                    $scrape->title = $raw_match[2];
                    $scrape->url = 'http://us.battle.net/d3/en/forum/topic/' . $raw_match[1];
                    $scrape->hash = md5($scrape->url);
                }

                $results[] = $scrape;
            }
        }
        return $results;
    }

    /**
     * Get a complex scrape based on a basic scrape
     * @param object $basic
     * @return object complex scrape
     */
    public function get_complex_scrape($basic)
    {
        $raw_html = curl_get($basic->url);
        $parts = explode('#', $basic->url);
        $id = $parts[1];
/*
<span id="23"></span>
					
                	<div class="post-interior">
                            <table><tr><td class="post-character">

	<div class="post-user">

            <div class="avatar">
                <div class="avatar-interior">
                   <img src="http://us.media2.battle.net/cms/user_avatar/TM0PIDH5S7YF1299809371044.gif" alt="" width="64" />
                </div>
            </div>

        <div class="character-info">


    <div class="user-name">
		<span class="char-name-code" style="display: none">
			Zarhym 
		</span>



	<div id="context-4" class="ui-context">
		<div class="context">
			<a href="javascript:;" class="close" onclick="return CharSelect.close(this);"></a>

			<div class="context-user">
				<strong>Zarhym</strong>
			</div>





			<div class="context-links">
					<a href="/d3/en/search?f=post&amp;a=Zarhym&amp;sort=time" title="View posts" rel="np"
					   class="icon-posts link-first link-last"
					   >
						View posts
					</a>
			</div>
		</div>

	</div>

        <a href="javascript:;" class="context-link" rel="np">
        	Zarhym
        </a>
    </div>


            <div class="blizzard-title">Community Manager</div>


        </div>
	</div>
							</td><td>
                                <div class="post-edited">
                                </div>
                                
                                <div class="post-detail">
                                    We love it when people are enthusiastic about all of our games. So of course we want there to be a similarity in the functionality and feel of each community site which supports them. :)<br/><br/>We want it to be a rather seamless transition when you visit any of the <a href="http://battle.net">http://battle.net</a> 2.0 sites. If you look at the WoW, SC2, and DIII sites carefully though, you'll see how the flavors of each game -- in particular the look of the UI, art style, font, and color schemes -- really enhance each site's atmosphere.<br/><br/>It's worth noting too you'll only be able to post here if you have a DIII license... once the game is released! Along with that players will have all-new Diablo character avatars. <br/><br/>I'm very happy to finally have this community site join the new battle.net family, for serious. :D<br/><br/>Oh, also this:<br/><br/><blockquote>Blizzard wants ease of communication between all their games via the new battle.net. Having sites that are familiar across all (newer) games is just part of that synergy.</blockquote><br/><br/><blockquote data-quote="30818810830" class="quote-public"><div><span class="bml-quote-date">08/24/2011 11:34 PM</span>Posted by <a href="3082248380?page=1#6">Fendria</a></div>Does your wow account have to be active to post on here, I wonder? Mine is gonna expire in a day or so, I was just on a free 7-day thing to check out the new patch.</blockquote><br/>Yes, for now you just need an active World of Warcraft or StarCraft II license to post here. Once beta begins, those chosen to participate will get access as well.
                                </div>
                            </td><td class="post-info">
                                <div class="post-info-int">
                                    <div class="postData">
										
										<a href="#23">
											#23
										</a>
                                        
										<div class="date" data-tooltip="8/25/11 12:49 AM">
                                        	22 hours ago
                                        </div>
										
                                    </div>

*/
        if (preg_match('/<span id="' . $parts[1] . '"><\/span>(.+?)<span class="clear">/sim', $raw_html, $match))
        {
            var_dump($match);
            exit();
        }
    }
}
