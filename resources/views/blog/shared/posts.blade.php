<div id="posts">
    {{-- Display latest Blog posts ---}}
    @foreach ($postInfo["posts"] as $post)
        <article class="post">
            <div class="postImage">
            </div>
            <div class="postEntry">

                
                <h2><a href="/{{ $post->titleURL }}">{{ $post->title }}</a></h2>
                <time class="postDate">{{ $post->publishDate }}</time>
                <div class="postInfo">
                    {{-- only display the category name if it's not empty --}}


                    @if ($post->categoryNames != '')
                        {{-- Turn list of category names into an array --}}
                        <?php $categoryNames = explode(",", $post->categoryNames) ?>

                        <div class="postCategories">
                            Filed Under:
                            
                                @for ($ci = 0; $ci < count($categoryNames); $ci++)
                                    {!! $categoryNames[$ci] !!}

                                    @if ($ci < count($categoryNames)-1)|@endif
                                @endfor
                            
                            
                            {{-- <a href="/category/{{ $post->categoryURL }}"> --}}
                        </div>
                    @endif
                </div>

                @if ($post->teaser != '')
                    <div class="postTeaser">{{ $post->teaser }}</div>
                @endif

                
                
                @if ($post->discussionCount > 0)
                    <div class="postCommentCount">Comments: {{ $post->discussionCount }}</div>
                @endif
            </div>
        </article>

        <?php $postInfo["thisPostCount"]++ ?>

        {{--  If this isn't the last post, then display a divider between the previous post --}}        
        @if ($postInfo['sizeOfPosts'] > $postInfo["thisPostCount"])
            <div class="postDivider"></div>
        @endif
        
    @endforeach {{-- $postInfo as $post --}}
</div>