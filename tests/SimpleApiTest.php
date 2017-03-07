<?php
class SimpleApiTest extends TestCase
{
    public function testEchoApi()
    {
        // リクエスト生成
        $response = $this->call('GET', '/apis/echo');
        $this->assertEquals(200, $response->getStatusCode());
        
        //response->getContent()もある
        //$decode = $response->getData(true);とするとarrayで返る
        
        $obj = $response->getData();
        $this->assertEquals("OK", $obj->status);
        $this->assertEquals("No problem", $obj->message);
    }
}
