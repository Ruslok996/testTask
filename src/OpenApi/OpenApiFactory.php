<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\Parameter;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\Model\RequestBody;
use ApiPlatform\OpenApi\Model\Response;
use ApiPlatform\OpenApi\OpenApi;

class OpenApiFactory implements OpenApiFactoryInterface
{
    private OpenApiFactoryInterface $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $paths = $openApi->getPaths();
        $controllers = [
            '/api/transactions/{id}/cancel' => [
                'operationId' => 'cancelTransaction',
                'method' => 'post',
                'tags' => ['Transactions'],
                'summary' => 'Cancel a transaction',
                'description' => 'Cancels a specified transaction.',
                'parameters' => [
                    new Parameter('id', 'path', 'integer', 'Transaction ID', true),
                ],
                'responses' => [
                    '200' => new Response('Transaction successfully canceled', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => ['type' => 'string'],
                                ],
                            ],
                        ],
                    ])),
                    '400' => new Response('Transaction already canceled', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => ['type' => 'string'],
                                ],
                            ],
                        ],
                    ])),
                ],
            ],
            '/api/deposit' => [
                'operationId' => 'depositFunds',
                'method' => 'post',
                'tags' => ['Cash'],
                'summary' => 'Deposit funds',
                'description' => 'Allows a user to deposit funds into their account.',
                'requestBody' => new RequestBody(
                    'Deposit request',
                    new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'accountId' => ['type' => 'integer'],
                                    'amount' => ['type' => 'number'],
                                ],
                            ],
                        ],
                    ])
                ),
                'responses' => [
                    '201' => new Response('Deposit initiated', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => ['type' => 'string'],
                                ],
                            ],
                        ],
                    ])),
                    '400' => new Response('Invalid data', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => ['type' => 'string'],
                                ],
                            ],
                        ],
                    ])),
                ],
            ],
            '/api/cash-registers/{id}/balance' => [
                'operationId' => 'getCashRegisterBalance',
                'method' => 'get',
                'tags' => ['Cash Register'],
                'summary' => 'Get cash register balance',
                'description' => 'Retrieves the total balance of a specified cash register.',
                'parameters' => [
                    new Parameter('id', 'path', 'integer', 'Cash Register ID', true),
                ],
                'responses' => [
                    '200' => new Response('Successfully retrieved balance', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'balance' => ['type' => 'number'],
                                ],
                            ],
                        ],
                    ])),
                    '404' => new Response('Cash register not found', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => ['type' => 'string'],
                                ],
                            ],
                        ],
                    ])),
                ],
            ],
            '/api/user/balance' => [
                'operationId' => 'getUserBalance',
                'method' => 'get',
                'tags' => ['User Management'],
                'summary' => 'Get user balance',
                'description' => 'Retrieves the total balance of the authenticated user.',
                'responses' => [
                    '200' => new Response('User balance retrieved successfully', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'balance' => ['type' => 'number', 'example' => 3500.75],
                                ],
                            ],
                        ],
                    ])),
                    '401' => new Response('User not authenticated', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => ['type' => 'string', 'example' => 'User not authenticated.'],
                                ],
                            ],
                        ],
                    ])),
                ],
            ],
            '/api/user/accounts' => [
                'operationId' => 'getUserAccounts',
                'method' => 'get',
                'tags' => ['User Management'],
                'summary' => 'Get user accounts',
                'description' => 'Retrieves a list of accounts associated with the authenticated user.',
                'responses' => [
                    '200' => new Response('Successfully retrieved user accounts', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'accounts' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id' => ['type' => 'integer', 'example' => 101],
                                                'balance' => ['type' => 'number', 'example' => 1200.50],
                                                'isActive' => ['type' => 'boolean', 'example' => true],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ])),
                    '401' => new Response('User not authenticated', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => ['type' => 'string', 'example' => 'User not authenticated.'],
                                ],
                            ],
                        ],
                    ])),
                ],
            ],
            '/api/user/transactions' => [
                'operationId' => 'getUserTransactions',
                'method' => 'get',
                'tags' => ['Transactions'],
                'summary' => 'Get user transactions',
                'description' => 'Retrieves a list of all transactions associated with the authenticated user.',
                'responses' => [
                    '200' => new Response('Successfully retrieved user transactions', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'transactions' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id' => ['type' => 'integer', 'example' => 1050],
                                                'type' => ['type' => 'string', 'example' => 'deposit'],
                                                'amount' => ['type' => 'number', 'example' => 250.00],
                                                'createdAt' => ['type' => 'string', 'format' => 'date-time', 'example' => '2024-02-08T14:45:00Z'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ])),
                    '401' => new Response('User not authenticated', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => ['type' => 'string', 'example' => 'User not authenticated.'],
                                ],
                            ],
                        ],
                    ])),
                ],
            ],
            '/api/dashboard' => [
                'operationId' => 'getDashboard',
                'method' => 'get',
                'tags' => ['Dashboard'],
                'summary' => 'Get dashboard data',
                'description' => 'Retrieves statistical data for the system dashboard, including financial and user-related insights.',
                'responses' => [
                    '200' => new Response('Successfully retrieved dashboard data', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'totalUsers' => ['type' => 'integer', 'example' => 500],
                                    'totalTransactions' => ['type' => 'integer', 'example' => 12000],
                                    'totalRevenue' => ['type' => 'number', 'example' => 250000.75],
                                    'recentActivity' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'event' => ['type' => 'string', 'example' => 'User registered'],
                                                'timestamp' => ['type' => 'string', 'format' => 'date-time', 'example' => '2024-02-08T18:00:00Z'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ])),
                    '401' => new Response('User not authenticated', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => ['type' => 'string', 'example' => 'User not authenticated.'],
                                ],
                            ],
                        ],
                    ])),
                ],
            ],
            '/api/transfer' => [
                'operationId' => 'transferFunds',
                'method' => 'post',
                'tags' => ['Cash'],
                'summary' => 'Transfer funds',
                'description' => 'Transfers funds from one account to another.',
                'requestBody' => new RequestBody(
                    'Transfer request',
                    new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'sourceAccountId' => ['type' => 'integer'],
                                    'destinationAccountId' => ['type' => 'integer'],
                                    'amount' => ['type' => 'number'],
                                ],
                            ],
                        ],
                    ])
                ),
                'responses' => [
                    '201' => new Response('Transfer initiated', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => ['type' => 'string', 'example' => 'Transfer initiated.'],
                                ],
                            ],
                        ],
                    ])),
                    '400' => new Response('Invalid data', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => ['type' => 'string', 'example' => 'Invalid data.'],
                                ],
                            ],
                        ],
                    ])),
                    '401' => new Response('Unauthorized', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => ['type' => 'string', 'example' => 'User not authenticated.'],
                                ],
                            ],
                        ],
                    ])),
                ],
            ],
            '/api/users/{id}/activate' => [
                'operationId' => 'activateUser',
                'method' => 'post',
                'tags' => ['User Management'],
                'summary' => 'Activate a user',
                'description' => 'Activates a user account. Admin access required.',
                'parameters' => [
                    new Parameter('id', 'path', 'integer', 'User ID', true),
                ],
                'responses' => [
                    '200' => new Response('User activated successfully', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => ['type' => 'string', 'example' => 'User activated successfully.'],
                                ],
                            ],
                        ],
                    ])),
                    '403' => new Response('Forbidden', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => ['type' => 'string', 'example' => 'Access denied.'],
                                ],
                            ],
                        ],
                    ])),
                ],
            ],
            '/api/users/{id}/deactivate' => [
                'operationId' => 'deactivateUser',
                'method' => 'post',
                'tags' => ['User Management'],
                'summary' => 'Deactivate a user',
                'description' => 'Deactivates a user account. Admin access required.',
                'parameters' => [
                    new Parameter('id', 'path', 'integer', 'User ID', true),
                ],
                'responses' => [
                    '200' => new Response('User deactivated successfully', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => ['type' => 'string', 'example' => 'User deactivated successfully.'],
                                ],
                            ],
                        ],
                    ])),
                    '403' => new Response('Forbidden', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => ['type' => 'string', 'example' => 'Access denied.'],
                                ],
                            ],
                        ],
                    ])),
                ],
            ],
            '/api/user/dashboard' => [
                'operationId' => 'getUserDashboard',
                'method' => 'get',
                'tags' => ['User Management'],
                'summary' => 'Get user dashboard',
                'description' => 'Retrieves dashboard data for the authenticated user.',
                'responses' => [
                    '200' => new Response('Successfully retrieved dashboard data', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'totalBalance' => ['type' => 'number', 'example' => 4500.00],
                                    'totalTransactions' => ['type' => 'integer', 'example' => 120],
                                    'recentTransactions' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id' => ['type' => 'integer', 'example' => 1001],
                                                'type' => ['type' => 'string', 'example' => 'withdrawal'],
                                                'amount' => ['type' => 'number', 'example' => 500.00],
                                                'createdAt' => ['type' => 'string', 'format' => 'date-time', 'example' => '2024-02-08T12:30:00Z'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ])),
                    '401' => new Response('User not authenticated', new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => ['type' => 'string', 'example' => 'User not authenticated.'],
                                ],
                            ],
                        ],
                    ])),
                ],
            ],
        ];

        foreach ($controllers as $path => $config) {
            $operation = new Operation(
                $config['operationId'],
                $config['tags'],
                $config['responses'],
                $config['summary'],
                $config['description'],
                null,
                $config['parameters'] ?? [],
                $config['requestBody'] ?? null
            );

            $pathItem = new PathItem(
                null,
                null,
                null,
                'get' === $config['method'] ? $operation : null,
                'put' === $config['method'] ? $operation : null,
                'post' === $config['method'] ? $operation : null,
                'delete' === $config['method'] ? $operation : null,
                null, // options
                null, // head
                null, // patch
                null  // trace
            );

            $paths->addPath($path, $pathItem);
        }

        return $openApi;
    }
}
