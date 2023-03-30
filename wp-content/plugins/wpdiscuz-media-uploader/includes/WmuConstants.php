<?php

if (!defined("ABSPATH")) {
    exit();
}

interface WmuConstants {
    const OPTION_MAIN                       = "wmu_options";
    const OPTION_VERSION                    = "wmu_version";    
    const OPTION_REGENERATE_KEYS            = "wmu_regenerate_attachment_metakeys";
	const OPTION_ENABLE_ALL_MIMES			= "wmu_enable_all_mimes";
    
    // DEPRECATED KEYS
    const METAKEY_IMAGE                     = "attachment_images";
    const METAKEY_VIDEO                     = "attachment_videos_audios";
    const METAKEY_FILE                      = "attachment_files";
        
    const KEY_VIDEOS                        = "videos";
    const KEY_FILES                         = "files";
    
}