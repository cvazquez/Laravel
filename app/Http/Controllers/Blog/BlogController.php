<?php

namespace App\Http\Controllers\Blog;

//use App\User;
use DB;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{


    public function home(){
        
        $posts = $this->getPosts();
        //$navCategories = $this->getNavCategories();

        $postInfo = array();
        $postInfo["thisPostCount"] = 0; 
        $postInfo["sizeOfPosts"] = sizeof($posts);
        $postInfo["posts"] = $posts;

        return view('welcome', 
            [   'postInfo' => $postInfo, 
                'navCategories' => $this->getNavCategories()]);
    }


    public function entry($titleURL){

        //$navCategories = $this->getNavCategories();
        $post = $this->getPost($titleURL);

        if(sizeOf($post) === 1) {
            $title = $post[0]->title;
            return view("blog/entry",
                    [   'titleURL' => $post[0]->titleURL, 
                        'title' => $title, 
                        'post' => $post, 
                        'navCategories' => $this->getNavCategories()]);
        } else {
            return "No Blog Post found with this name";
        }
    }

    public function categories(){

        //$navCategories = $this->getNavCategories();
        $categories = $this->getCategories();

        return view("blog/categories",
                        [   'title' => "Blog Categories",                            
                            'navCategories' => $this->getNavCategories(),
                            'categories' => $categories
                        ]);
    }


    public function categoryEntries($categoryURL){

        //$navCategories = $this->getNavCategories();
        $posts = $this->getCategoryEntries($categoryURL);

        $postInfo = array();
        $postInfo["thisPostCount"] = 0; 
        $postInfo["sizeOfPosts"] = sizeof($posts);
        $postInfo["posts"] = $posts;

        return view("blog/categoryentries",
                        [   'title' => $postInfo["posts"][0]->categoryName . "Posts",
                            'postInfo' => $postInfo,
                            'navCategories' => $this->getNavCategories(),
                            'categoryName' => $postInfo["posts"][0]->categoryName
                        ]);

    }


    private function getPosts(){

        // Todo - Use Eloquent ORM and put in model
        $posts = DB::connection('mysql')->select('
                SELECT e.title, eu.titleURL, e.teaser, Date_Format(e.publishAt, "%b %e, %Y") AS publishDate,
                        c.name AS categoryName, cu.name AS categoryURL,
                        count(distinct(ed.id)) AS discussionCount,
                        fsp.squareURL AS imageURL, fsp.title AS imageTitle,
                        group_concat(DISTINCT concat("<a href=\'/category/", cu.name ,"\'>", c.name, "</a>")) AS categoryNames
                FROM entries e
                INNER JOIN entryurls eu ON eu.entryId = e.id AND eu.isActive = 1 AND eu.deletedAt IS NULL
                LEFT JOIN entrycategories ec ON ec.entryId = e.id AND ec.deletedAt IS NULL
                LEFT JOIN categories c ON c.id = ec.categoryId
                LEFT JOIN categoryurls cu ON cu.categoryId = c.id AND cu.isActive = 1 AND cu.deletedAt iS NULL
                LEFT JOIN entrydiscussions ed ON ed.entryId = e.id AND ed.deletedAt IS NULL
                LEFT JOIN entryflickrsets efs ON efs.entryId = e.id AND efs.deletedAt IS NULL
                LEFT JOIN flickrsets fs ON fs.id = efs.flickrSetId AND fs.deletedAt IS NULL
                LEFT JOIN flickrcollections fc ON fc.id = fs.flickrCollectionid AND fc.deletedAt IS NULL
                LEFT JOIN flickrsetphotos fsp ON fsp.flickrSetId = fs.id AND fsp.deletedAt IS NULL
                WHERE e.deletedAt IS NULL
                GROUP BY e.id
                ORDER BY e.publishAt desc
                LIMIT 10');

        return $posts;
    }

    private function getNavCategories(){

        $navCategories = DB::connection('mysql')->select('
                SELECT DISTINCT if(hc.altName IS NULL, c.name, hc.altName) AS categoryName, cu.name AS categoryURL
                FROM headercategories hc
                INNER JOIN categories c ON c.id = hc.categoryId AND c.deletedAt IS NULL
                INNER JOIN categoryurls cu ON cu.categoryId = c.id AND cu.isActive = 1 AND cu.deletedAt IS NULL
                INNER JOIN entrycategories ec ON ec.categoryId = c.id AND ec.deletedAt IS NULL
                INNER JOIN entries e ON e.id = ec.entryId AND e.deletedAt IS NULL
                WHERE hc.deletedAt IS NULL
                ORDER BY hc.sequence desc');

        return $navCategories;
    }

    private function getCategories(){

        $categories = DB::connection('mysql')->select('
                SELECT c.name, cu.name AS URL, count(distinct(e.id)) AS entryCount
                FROM categories c
                INNER JOIN categoryurls cu ON cu.categoryId = c.id AND cu.isActive = 1 AND cu.deletedAt IS NULL
                INNER JOIN entrycategories ec ON ec.categoryId = c.id AND ec.deletedAt IS NULL
                INNER JOIN entries e ON e.id = ec.entryId AND e.deletedAt IS NULL
                WHERE c.deletedAt IS NULL
                GROUP BY c.id
                ORDER BY c.name');

        return $categories;
    }


    private function getPost($titleURL){
        // Todo - Use Eloquent ORM and put in nodel
        $post = DB::connection('mysql')->select('
                SELECT e.title, e.content, eu.titleURL, e.teaser, Date_Format(e.publishAt, "%b %e, %Y") AS publishDate,
                        c.name AS categoryName, cu.name AS categoryURL,
                        count(distinct(ed.id)) AS discussionCount,
                        fsp.squareURL AS imageURL, fsp.title AS imageTitle
                FROM entryurls eu
                INNER JOIN entries e ON eu.entryId = e.id AND e.deletedAt IS NULL
                LEFT JOIN entrycategories ec ON ec.entryId = e.id AND ec.deletedAt IS NULL
                LEFT JOIN categories c ON c.id = ec.categoryId
                LEFT JOIN categoryurls cu ON cu.categoryId = c.id AND cu.isActive = 1 AND cu.deletedAt iS NULL
                LEFT JOIN entrydiscussions ed ON ed.entryId = e.id AND ed.deletedAt IS NULL
                LEFT JOIN entryflickrsets efs ON efs.entryId = e.id AND efs.deletedAt IS NULL
                LEFT JOIN flickrsets fs ON fs.id = efs.flickrSetId AND fs.deletedAt IS NULL
                LEFT JOIN flickrcollections fc ON fc.id = fs.flickrCollectionid AND fc.deletedAt IS NULL
                LEFT JOIN flickrsetphotos fsp ON fsp.flickrSetId = fs.id AND fsp.deletedAt IS NULL
                WHERE eu.titleURL = ? AND eu.isActive = 1 AND eu.deletedAt IS NULL
                GROUP BY e.id
                LIMIT 1', [$titleURL]);
        
        return $post;
    }


    private function getCategoryEntries($categoryURL){

        // Todo - Use Eloquent ORM and put in nodel
        $categoryEntries = DB::connection('mysql')->select('
            SELECT e.title, eu.titleURL, e.teaser, Date_Format(e.publishAt, "%b %e, %Y") AS publishDate,
                        c.name AS categoryName, cu.name AS categoryURL,
                        count(distinct(ed.id)) AS discussionCount,
                        fsp.squareURL AS imageURL, fsp.title AS imageTitle,
                        group_concat(DISTINCT concat("<a href=\'/category/", cu.name ,"\'>", c.name, "</a>")) AS categoryNames
                FROM categoryurls cu 
                INNER JOIN categories c ON c.id = cu.categoryId AND c.deletedAt IS NULL
                INNER JOIN entrycategories ec ON ec.categoryId = c.id AND ec.deletedAt IS NULL
                INNER JOIN entries e ON ec.entryId = e.id AND e.deletedAt IS NULL
                INNER JOIN entryurls eu ON eu.entryId = e.id AND eu.isActive = 1 AND eu.deletedAt IS NULL
                LEFT JOIN entrydiscussions ed ON ed.entryId = e.id AND ed.deletedAt IS NULL
                LEFT JOIN entryflickrsets efs ON efs.entryId = e.id AND efs.deletedAt IS NULL
                LEFT JOIN flickrsets fs ON fs.id = efs.flickrSetId AND fs.deletedAt IS NULL
                LEFT JOIN flickrcollections fc ON fc.id = fs.flickrCollectionid AND fc.deletedAt IS NULL
                LEFT JOIN flickrsetphotos fsp ON fsp.flickrSetId = fs.id AND fsp.deletedAt IS NULL
                WHERE cu.name = ? AND cu.deletedAT IS NULL
                GROUP BY e.id
                ORDER BY e.publishAt desc
                LIMIT 10', [$categoryURL]);

        return $categoryEntries;
    }
}