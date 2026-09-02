<?php

namespace App\Domain\Stabs;

class ControllerStab
{
    public static function getStub(): string
    {
        return <<<'STUB'
        <?php

        namespace App\Http\Controllers;

        use {{ request }};
        use {{ service }};
        use Illuminate\Support\Facades\DB;
        use App\Traits\ApiResponse;
        use Throwable;

        class {{ class }} extends Controller
        {
            use ApiResponse;
            private ${{ serviceVar }};

            public function __construct({{ serviceClass }} ${{ serviceVar }})
            {
                $this->{{ serviceVar }} = ${{ serviceVar }};
            }

            public function index({{ requestClass }} $request)
            {
                try {
                    $data = $this->{{ serviceVar }}->getAll{{ model }}();

                    return $this->apiResponse($data, '{{ model }}', 'success', '{{ model }} list fetched successfully.');
                } catch (Throwable $exception) {
                    return $this->errorResponse($exception);
                }
            }

            public function show({{ requestClass }} $request)
            {
                try {
                    $validated = $request->validated();

                    $data = $this->{{ serviceVar }}->get{{ model }}ById(
                        $validated['id']
                    );

                    if (!$data) {
                        return $this->apiResponse(null, '{{ model }}', 'warning', '{{ model }} not found.', 404);
                    }

                    return $this->apiResponse($data, '{{ model }}', 'success', '{{ model }} fetched successfully.');
                } catch (Throwable $exception) {
                    return $this->errorResponse($exception);
                }
            }

            public function create({{ requestClass }} $request)
            {
                try {
                    $validated = $request->validated();

                    $data = DB::transaction(function () use ($validated) {
                        return $this->{{ serviceVar }}->add{{ model }}({{ createArguments }});
                    });

                    return $this->apiResponse($data, '{{ model }}', 'success', '{{ model }} created successfully.', 201);
                } catch (Throwable $exception) {
                    return $this->errorResponse($exception);
                }
            }

            public function update({{ requestClass }} $request)
            {
                try {
                    $validated = $request->validated();

                    $data = DB::transaction(function () use ($validated) {
                        return $this->{{ serviceVar }}->edit{{ model }}(
                            $validated['id']{{ updateArguments }}
                        );
                    });

                    return $this->apiResponse($data, '{{ model }}', 'success', '{{ model }} updated successfully.');
                } catch (Throwable $exception) {
                    return $this->errorResponse($exception);
                }
            }

            public function delete({{ requestClass }} $request)
            {
                try {
                    $validated = $request->validated();

                    $data = DB::transaction(function () use ($validated) {
                        return $this->{{ serviceVar }}->delete{{ model }}(
                            $validated['id']
                        );
                    });

                    if (!$data) {
                        return $this->apiResponse(null, '{{ model }}', 'warning', '{{ model }} not found.', 404);
                    }

                    return $this->apiResponse($data, '{{ model }}', 'success', '{{ model }} deleted successfully.');
                } catch (Throwable $exception) {
                    return $this->errorResponse($exception);
                }
            }
        }
        STUB;
    }
}
