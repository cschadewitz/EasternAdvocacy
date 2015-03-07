<?php
    namespace Lasso\Petitions\Updates;

    use Seeder;
    use Lasso\Petitions\Models\Petitions;

    class SeedPetitions extends Seeder
    {
        public function run()
        {
            $petition = Petitions::create([
                'title' => 'Random Petition',
                'summary' => 'Summary of random petition',
                'body' => 'Detailed description of random petition',
                'published' => true,
                'publicationDate' => date('Y-m-d H:i:s'),
                'signatures' => 0,
            ]);
        }
    }