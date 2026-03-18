<?php
require_once __DIR__ . "/../../include/CommonUtils.class.php";

class TGDBUtils
{
	public static function GetCover($game, $type = '', $side = '', $return_lazy_results = false, $return_placeholder = true, $return_size = 'thumb')
	{
		if(isset($game->boxart))
		{
			foreach($game->boxart as $art)
			{
				if($return_lazy_results && !isset($ret))
				{
					$ret = $art;
				}

				if($art->type == $type)
				{
					if(isset($art->side) && $art->side == $side)
					{
						$ret = $art;
						break;
					}
					else if($return_lazy_results)
					{
						$ret = $art;
					}
				}
			}
			if(isset($ret))
			{
				if (isset($ret->base_url))
					$prefix = CommonUtils::getImagesBaseUrl()[$ret->base_url][$return_size];
				if (!isset($prefix))
				{
					if (!isset($ret->base_url) || $ret->base_url == 0)
						$prefix = CommonUtils::$BOXART_BASE_URL;
					else
						$prefix = CommonUtils::$LOCAL_BOXART_BASE_URL;
					$prefix = $prefix . $return_size . "/";
				}
				return $prefix . $ret->filename;
			}
		}
		if($return_placeholder)
		{
			if(isset($game->game_title))
			{
				return "https://dummyimage.com/200x200?text=" . urlencode($game->game_title);
			}
			elseif(isset($game->name))
			{
				return "https://dummyimage.com/200x200?text=" . urlencode($game->name);
			}
			return "https://dummyimage.com/200x200";
		}
	}

	public static function GetAllCovers($game, $type = '', $side = '')
	{
		$ret = array();
		$BASE_URL = CommonUtils::getImagesBaseURL();
		if(isset($game->boxart))
		{
			foreach($game->boxart as $art)
			{
				if($art->type == $type)
				{
					if($art->side == $side)
					{
						$art->thumbnail = new \stdClass();
						$art->original = $BASE_URL[$art->base_url]["original"] . $art->filename;
						$art->small = $BASE_URL[$art->base_url]["small"] . $art->filename;
						$art->cropped_center_thumb = $BASE_URL[$art->base_url]["cropped_center_thumb"] . $art->filename;
						$art->thumbnail = $BASE_URL[$art->base_url]["thumb"] . $art->filename;
						$art->medium = $BASE_URL[$art->base_url]["medium"] . $art->filename;
						$art->large = $BASE_URL[$art->base_url]["large"] . $art->filename;
						$ret[] = $art;
					}
				}
			}
		}
		return $ret;
	}

	public static function GetPlaceholderImage($Name, $size)
	{
		return "https://via.placeholder.com/200x200?text=" . urlencode($Name);
	}
}

?>
