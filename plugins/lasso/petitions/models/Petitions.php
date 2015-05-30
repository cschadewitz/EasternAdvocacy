<?php
    namespace Lasso\Petitions\Models;

    use Model;
    use Str;
    use Mail;
    use Illuminate\Support\Facades\DB as Db;
    use October\Rain\Database\Traits\Sluggable;

    class Petitions extends Model
    {
        use \October\Rain\Database\Traits\Validation;

        public $rules = [
            'title' => 'required|between:4,200',
            'summary' => 'required',
            'body' => 'required',
            'goal' => 'required',
        ];

        protected $table = 'lasso_petitions_petitions';

        protected $primaryKey = 'pid';

        protected $slugs = ['slug' => 'title'];

        protected $dates = [
            'publicationDate',
            'created_at',
            'updated_at',
        ];

        public $hasMany = [
            'signatures' => ['Lasso\Petitions\Models\Signatures', 'order' => 'created_at']
        ];

        public function beforeCreate()
        {
            // Generate a URL slug for this model
            $this->slug = Str::slug($this->title);
        }

        public function afterUpdate(){
            $util = new Utility();
            //Email effected petitioners
            $util->emailSignatures($this->pid);
            //Delete effected signatures
            \Lasso\Petitions\Models\Signatures::DeleteUsers($this->pid);
            $this->signatures = 0;
            $this->save();
        }

        public function scopeListPosts($query, $options) {
            extract(array_merge([
                'page' => 1,
                'petitionsPerPage' => 10,
            ], $options), EXTR_OVERWRITE);

            return $query->paginate($petitionsPerPage, $page);
        }

        public function setUrl($pageName, $controller) {
            $params = [
                'pid' => $this->id,
                'slug' => $this->slug,
            ];
            return $this->url = $controller->pageUrl($pageName, $params);
        }

        public function scopeTitle($query, $title)
        {
            return $query->where('title', '=', $title);
        }

        public function scopePid($query, $pid)
        {
            return $query->where('pid', '=', $pid);
        }

        public function scopeGetPetition($query, $pid)
        {
            return $query->where('pid', '=', $pid);
        }

        public function scopeUrl($query, $slug)
        {
            return $query->where('slug', '=', $slug);
        }

        public function scopeActive($query)
        {
            return $query->where('published', '=', 1);
        }

        public static function sortByProgress($numberOfPetitions)
        {
            return Db::select('SELECT lasso_petitions_petitions.*, lasso_petitions_petitions.signatures/lasso_petitions_petitions.goal AS sig_count
                                FROM lasso_petitions_petitions
                                ORDER BY sig_count DESC
	                            LIMIT ?', [$numberOfPetitions]);
        }

        public static function sortBySigCount($numberOfPetitions)
        {
            return Db::select('SELECT lasso_petitions_petitions.*, lasso_petitions_petitions.signatures AS sig_count
                                FROM lasso_petitions_petitions
                                ORDER BY sig_count DESC
	                            LIMIT ?', [$numberOfPetitions]);
        }
    }

    class Utility
    {

        public function emailSignatures($pid)
        {
            $results = \Lasso\Petitions\Models\Signatures::Pid($pid)->get();
            $petition = \Lasso\Petitions\Models\Petitions::Pid($pid)->first();

            foreach ($results as $r) {
                $params = ['name' => $r->name, 'email' => $r->email, 'petitionName' => $petition->title, 'slug' => $petition->slug];
                Mail::sendTo([$r->email => $r->name], 'lasso.petitions::mail.petition_changed', $params);
            }
        }

    }