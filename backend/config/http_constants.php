<?php

use Symfony\Component\HttpFoundation\Response;

return [
    'ok' => Response::HTTP_OK, // 200
    'created' => Response::HTTP_CREATED, // 201,
    'unauthorized' => Response::HTTP_UNAUTHORIZED, //401
    'forbidden' => Response::HTTP_FORBIDDEN, // 403
    'not_found' => Response::HTTP_NOT_FOUND, //404
    'unprocessable_entity' => Response::HTTP_UNPROCESSABLE_ENTITY //422
];