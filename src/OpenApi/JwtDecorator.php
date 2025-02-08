<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\SecurityScheme;
use ApiPlatform\OpenApi\OpenApi;

class JwtDecorator implements OpenApiFactoryInterface
{
    private OpenApiFactoryInterface $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        $info = $openApi->getInfo();
        $info = $info->withTitle('Your API Title')->withVersion('1.0.0');
        $openApi = $openApi->withInfo($info);

        $securitySchemes = $openApi->getComponents()->getSecuritySchemes() ?? new \ArrayObject();
        $securitySchemes['bearerAuth'] = new SecurityScheme(
            type: 'http',
            scheme: 'bearer',
            bearerFormat: 'JWT'
        );

        $openApi = $openApi->withComponents(
            $openApi->getComponents()->withSecuritySchemes($securitySchemes)
        );

        $securityRequirement = ['bearerAuth' => []];
        return $openApi->withSecurity([$securityRequirement]);
    }
}
