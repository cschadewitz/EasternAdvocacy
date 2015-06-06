<?php
    namespace Lasso\Petitions\Models;

    use Model;
    use Str;
    use Mail;
    use Db;
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
            if($this->ignoreChanges())
                return;
            $changes = $this->getChanges();
            if((count($changes) == 1 && in_array("published", $changes)) || (count($changes) == 1 && in_array("active", $changes)))
            {
                return;
            }
            elseif(count($changes) == 2 && in_array("published", $changes) && in_array("active", $changes))
            {
                return;
            }
            else
            {
                    //Email effected petitioners
                    \Lasso\Petitions\Models\Signatures::EmailSignatures($this->pid);
                    //Delete effected signatures
                    \Lasso\Petitions\Models\Signatures::DeleteUsers($this->pid);
                    Petitions::ResetSignatureCount($this->pid);
                    return;
            }
        }

        public function ignoreChanges(){
            return $this->isDirty('signatures');
        }

        public function getChanges(){
            $changes = [];
            if($this->isDirty('title')){
                $changes[] = "title";
            }
            if($this->isDirty('summary')){
                $changes[] = "summary";
            }
            if($this->isDirty('body')){
                $changes[] = "body";
            }
            if($this->isDirty('active')){
                $changes[] = "active";
            }
            if($this->isDirty('goal')){
                $changes[] = "goal";
            }
            if($this->isDirty('published')){
                $changes[] = "published";
            }
            return $changes;
        }

        public function scopeListPosts($query, $options) {
            extract(array_merge([
                'page' => 1,
                'petitionsPerPage' => 10,
            ], $options), EXTR_OVERWRITE);

            return $query->paginate($petitionsPerPage, $page);
        }

        public function scopeResetSignatureCount($query, $pid){
            $query->where('pid', '=', $pid)->update(array('signatures' => 0));
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

        public function scopePublished($query)
        {
            return $query->where('published', '=', '1');
        }

        public function scopeActive($query)
        {
            return $query->where('active', '=', 1);
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