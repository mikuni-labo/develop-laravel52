<?php
/**
 * これを別ドメインに置いて検証
 */
setcookie('test', null, time() - 1800, '/');