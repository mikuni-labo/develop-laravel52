<?php

namespace App\Lib\Api\VideoCloud;

/**
 * VideoCloud Asset Resources
 * 
 * @author Kuniyasu Wada
 */
Trait AssetsClient
{
    /** @var string The Assets ID */
    private $assetsId;

    /**
     * Get Poster List
     * 
     * Gets the poster file for a given video.
     * Note that you can only add one poster for a video.
     * Note: you can use /videos/ref:reference_id instead of /videos/video_id
     * 
     * @see     http://docs.brightcove.com/en/video-cloud/cms-api/references/cms-api/versions/v1/index.html#api-assetGroup-Get_Poster_List
     * @example https://cms.api.brightcove.com/v1/accounts/:account_id/videos/:video_id/assets/poster
     * @param   string $ref_id
     * @return  mixed
     */
    public function getPosterList($ref_id = '')
    {
        $id = !empty($ref_id) ? "ref:{$ref_id}" : $this->getVideoId();
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$id}/assets/poster";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('GET', $url, $header);
    }

    /**
     * Get Poster
     * 
     * Gets a poster file for a given video.
     * Note that you can only add one poster for a video.
     * Note: you can use /videos/ref:reference_id instead of /videos/video_id
     *
     * @see     http://docs.brightcove.com/en/video-cloud/cms-api/references/cms-api/versions/v1/index.html#api-assetGroup-Get_Poster
     * @example https://cms.api.brightcove.com/v1/accounts/:account_id/videos/:video_id/assets/poster/:asset_id
     * @param   string $ref_id
     * @return  mixed
     */
    public function getPoster($ref_id = '')
    {
        $id = !empty($ref_id) ? "ref:{$ref_id}" : $this->getVideoId();
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$id}/assets/poster/{$this->getAssetsId()}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('GET', $url, $header);
    }

    /**
     * Add Poster
     * 
     * Adds a poster file for a remote asset.
     * Ingested assets must be added via the Dynamic Ingest API.
     * Note: you can use /videos/ref:reference_id instead of /videos/video_id
     * 
     * @see     http://docs.brightcove.com/en/video-cloud/cms-api/references/cms-api/versions/v1/index.html#api-assetGroup-Add_Poster
     * @example https://cms.api.brightcove.com/v1/accounts/:account_id/videos/:video_id/assets/poster
     * @param   array $param
     * @param   string $ref_id
     * @return  mixed
     */
    public function addPoster($param = array(), $ref_id = '')
    {
        $id = !empty($ref_id) ? "ref:{$ref_id}" : $this->getVideoId();
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$id}/assets/poster";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('POST', $url, $header, $param);
    }

    /**
     * Update Poster
     * 
     * Updates the location of a remote poster file for a remote asset.
     * Note: you can use /videos/ref:reference_id instead of /videos/video_id
     * 
     * @see     http://docs.brightcove.com/en/video-cloud/cms-api/references/cms-api/versions/v1/index.html#api-assetGroup-Update_Poster
     * @example https://cms.api.brightcove.com/v1/accounts/:account_id/videos/:video_id/assets/poster/:asset_id
     * @param   array  $param
     * @param   string $ref_id
     * @return  mixed
     */
    public function updatePoster($param = array(), $ref_id = '')
    {
        $id = !empty($ref_id) ? "ref:{$ref_id}" : $this->getVideoId();
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$id}/assets/poster/{$this->getAssetsId()}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('PATCH', $url, $header, $param);
    }

    /**
     * Delete Poster
     * 
     * Deletes a poster file for a remote asset.
     * Note that you can only add one poster for a video.
     * Note: you can use /videos/ref:reference_id instead of /videos/video_id
     * 
     * @see     http://docs.brightcove.com/en/video-cloud/cms-api/references/cms-api/versions/v1/index.html#api-assetGroup-Delete_Poster
     * @example https://cms.api.brightcove.com/v1/accounts/:account_id/videos/:video_id/assets/poster/:asset_id
     * @param   string $ref_id
     * @return  mixed
     */
    public function deletePoster($ref_id = '')
    {
        $id = !empty($ref_id) ? "ref:{$ref_id}" : $this->getVideoId();
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$id}/assets/poster/{$this->getAssetsId()}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('DELETE', $url, $header);
    }

    /**
     * Get Thumbnail List
     * 
     * Gets the thumbnail for a given video.
     * Note that you can only add one thumbnail for a video.
     * Note: you can use /videos/ref:reference_id instead of /videos/video_id
     * 
     * @see     http://docs.brightcove.com/en/video-cloud/cms-api/references/cms-api/versions/v1/index.html#api-assetGroup-Get_Thumbnail_List
     * @example https://cms.api.brightcove.com/v1/accounts/:account_id/videos/:video_id/assets/thumbnail
     * @param   string $ref_id
     * @return  mixed
     */
    public function getThumbnailList($ref_id = '')
    {
        $id = !empty($ref_id) ? "ref:{$ref_id}" : $this->getVideoId();
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$id}/assets/thumbnail";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('GET', $url, $header);
    }

    /**
     * Get Thumbnail
     * 
     * Gets a thumbnail file for a given video.
     * Note that you can only add one thumbnail for a video.
     * Note: you can use /videos/ref:reference_id instead of /videos/video_id
     * 
     * @see     http://docs.brightcove.com/en/video-cloud/cms-api/references/cms-api/versions/v1/index.html#api-assetGroup-Get_Thumbnail
     * @example https://cms.api.brightcove.com/v1/accounts/:account_id/videos/:video_id/assets/thumbnail/:asset_id
     * @param   string $ref_id
     * @return  mixed
     */
    public function getThumbnail($ref_id = '')
    {
        $id = !empty($ref_id) ? "ref:{$ref_id}" : $this->getVideoId();
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$id}/assets/thumbnail/{$this->getAssetsId()}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('GET', $url, $header);
    }

    /**
     * Add Thumbnail
     * 
     * Adds a thumbnail file for a remote asset.
     * Ingested assets must be added via the Dynamic Ingest API.
     * Note: you can use /videos/ref:reference_id instead of /videos/video_id
     * 
     * @see     http://docs.brightcove.com/en/video-cloud/cms-api/references/cms-api/versions/v1/index.html#api-assetGroup-Add_Thumbnail
     * @example https://cms.api.brightcove.com/v1/accounts/:account_id/videos/:video_id/assets/thumbnail
     * @param   array $param
     * @param   string $ref_id
     * @return  mixed
     */
    public function addThumbnail($param = array(), $ref_id = '')
    {
        $id = !empty($ref_id) ? "ref:{$ref_id}" : $this->getVideoId();
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$id}/assets/thumbnail";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('POST', $url, $header, $param);
    }

    /**
     * Update Thumbnail
     * 
     * Updates the location of a remote thumbnail file for a remote asset.
     * Note: you can use /videos/ref:reference_id instead of /videos/video_id
     * 
     * @see     http://docs.brightcove.com/en/video-cloud/cms-api/references/cms-api/versions/v1/index.html#api-assetGroup-Update_Thumbnail
     * @example https://cms.api.brightcove.com/v1/accounts/:account_id/videos/:video_id/assets/thumbnail/:asset_id
     * @param   array  $param
     * @param   string $ref_id
     * @return  mixed
     */
    public function updateThumbnail($param = array(), $ref_id = '')
    {
        $id = !empty($ref_id) ? "ref:{$ref_id}" : $this->getVideoId();
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$id}/assets/thumbnail/{$this->getAssetsId()}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('PATCH', $url, $header, $param);
    }

    /**
     * Delete Thumbnail
     * 
     * Deletes a thumbnail file for a remote asset.
     * Note that you can only add one thumbnail for a video.
     * Note: you can use /videos/ref:reference_id instead of /videos/video_id
     * 
     * @see     http://docs.brightcove.com/en/video-cloud/cms-api/references/cms-api/versions/v1/index.html#api-assetGroup-Delete_Thumbnail
     * @example https://cms.api.brightcove.com/v1/accounts/:account_id/videos/:video_id/assets/thumbnail/:asset_id
     * @param   string $ref_id
     * @return  mixed
     */
    public function deleteThumbnail($ref_id = '')
    {
        $id = !empty($ref_id) ? "ref:{$ref_id}" : $this->getVideoId();
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$id}/assets/thumbnail/{$this->getAssetsId()}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('DELETE', $url, $header);
    }

    public function setAssetsId($assetsId)
    {
        $this->assetsId = $assetsId;
        return $this;
    }

    public function getAssetsId()
    {
        return $this->assetsId;
    }

}
