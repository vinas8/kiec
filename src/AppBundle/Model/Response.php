<?php
namespace AppBundle\Model;


use JMS\Serializer\Annotation as Serializer;

/**
 * Class Response
 *
 * @package AppBundle\Model
 *
 * @Serializer\AccessType("public_method")
 */
class Response
{
    /**
     * @var mixed
     *
     * @Serializer\Groups({"list", "details"})
     */
    private $metadata = [
        'code'    => 200,
        'message' => '',
    ];

    /**
     * @var mixed
     *
     * @Serializer\Groups({"list", "details"})
     */
    private $data = [];

    /**
     * @param  mixed $data
     * @param  mixed $metadata
     * @return Response
     */
    public static function create($data = [], $metadata = [])
    {
        $response = new Response();
        $response->setData($data);
        $response->setMetadata($metadata);

        return $response;
    }

    /**
     * @return mixed
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param  mixed $metadata
     * @return Response
     */
    public function setMetadata($metadata)
    {
        if (is_array($metadata)) {
            $this->metadata = array_merge($this->metadata, $metadata);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param  mixed $data
     * @return Response
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
