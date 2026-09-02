<?php

namespace App\Domain\Stabs;

class ServiceStab
{
    public static function getStub(): string
    {
        return <<<'STUB'
        <?php

        namespace {{ namespace }};

        use {{ interface }};

        class {{ class }}
        {
            private ${{ repositoryVar }};

            public function __construct({{ interfaceClass }} ${{ repositoryVar }})
            {
                $this->{{ repositoryVar }} = ${{ repositoryVar }};
            }

            public function getAll{{ model }}()
            {
                return $this->{{ repositoryVar }}->index();
            }

            public function get{{ model }}ById(string $id)
            {
                return $this->{{ repositoryVar }}->findByPrimaryId($id);
            }

            public function add{{ model }}({{ createParameters }})
            {
                return $this->{{ repositoryVar }}->create([{{ createData }}]);
            }

            public function edit{{ model }}(string $id{{ updateParameters }})
            {
                return $this->{{ repositoryVar }}->update($id, [{{ updateData }}]);
            }

            public function delete{{ model }}(string $id)
            {
                $data = $this->{{ repositoryVar }}->findByPrimaryId($id);

                if (!$data) {
                    return null;
                }

                $this->{{ repositoryVar }}->delete($id);

                return $data;
            }
        }
        STUB;
    }
}
