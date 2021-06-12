<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialIndexRequest;
use App\Http\Requests\SocialStoreRequest;
use App\Http\Requests\SocialShowRequest;
use App\Http\Requests\SocialUpdateRequest;
use App\Http\Requests\SocialDestroyRequest;
use App\Http\Resources\SocialResource;
use App\Models\Social;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SocialIndexRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(SocialIndexRequest $request)
    {

      $per_page = $request->query('per_page') ?: 15;

      $socials = Social::query();

      if (!Auth::user()->isAdmin()) {
        $socials->byUserRole(Auth::user()->roleKey());

    }

    // Filter by text
    if ($text = $request->query('text')) {
        $socials->where(function ($query) use ($text) {
            $query->where('title', 'like', '%' . $text . '%');
        });
    }
    $socials = $socials->paginate((int)$per_page);

        // Include relationship
        if ($request->has('with')) {
            $socials->load($request->query('with'));
        }

        return SocialResource::collection($socials);
    }
     /**
     * Store a newly created resource in storage.
     *
     * @param SocialStoreRequest $request
     * @return SocialResource
     * @throws Exception
     */
    public function store(SocialStoreRequest $request): SocialResource
    {

        DB::beginTransaction();

        try {
            $social = new Social();
            $social->title = $request->title;
            $social->description = $request->description;
            $social->text = $request->text;
            $social->tag_id = $request->tag_id;
            $social->post_id = $request->post_id;
            $social->user_id = $request->user_id;
            $social->sector_id = $request->sector_id;
            $social->author_id = $request->has('author_id') ? $request->author_id : Auth::id();
            $social->save();

            DB::commit();
        } catch (Exception $exception) {

            DB::rollBack();
            throw $exception;
        }

        return new SocialResource($social);

    }
     /**
     * Display the specified resource.
     *
     * @param SocialShowRequest $request
     * @param Social $social
     * @return SocialResource
     */
    public function show(SocialShowRequest $request, Social $social): SocialResource
    {
        // Include relationship
        if ($request->query('with')) {
            $social->load($request->query('with'));
        }

        return new SocialResource($social);
    }
      /**
     * Update the specified resource in storage.
     *
     * @param SocialUpdateRequest $request
     * @param Social $social
     * @return SocialResource
     * @throws Exception
     */
    public function update(SocialUpdateRequest $request, Social $social): SocialResource
    {

        DB::beginTransaction();

        try {

            $social->update($request->only(['title', 'sector_id']));

            $role_key = Auth::user()->roleKey();

            if (!Auth::user()->isAdmin() && $social->userRoleCanUpdateFlags($role_key)) {
                switch ($role_key) {
                    case Role::ROLE_POST_AUTHOR:
                        $social->update($request->only(['is_approved_by_post_author']));
                        break;
                        case Role::ROLE_GUEST_USER:
                            $social->update($request->only(['is_approved_by_guest_user']));
                            break;

                    default:
                        abort(403, 'Invalid Role');
                }
            }

            DB::commit();
        } catch (Exception $exception) {

            DB::rollBack();
            throw $exception;
        }

        return new SocialResource($social);
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param SocialDestroyRequest $request
     * @param Social $social
     * @return Response
     */
    public function destroy(SocialDestroyRequest $request, Social $social): Response
    {
        $social->delete();

        return response(null, 204);
    }

    // *****CREATE POST*****

    public function socials()
{
$socials = Social::orderBy('id','desc')->get();
return view('socials',compact('socials'));
}
// *****ADD POST AND TAG ID*******

public function addSocial(SocialStoreRequest $request)
{
    $social = new Social();
    $social->title = $request->title;
    $social->text = $request->text;
    $social->save();
    $tags = $request->tag;
    $tagNames = [];
    if (!empty($tags)) {
    foreach ($tags as $tagName)
    {
    $tag = Tag::firstOrCreate(['name'=>$tagName, 'slug'=>Str::slug($tagName)]);
    if($tag)
    {
    $tagNames[] = $tag->id;
    }
    }
    $social->tags()->syncWithoutDetaching($tagNames);
    }
    return redirect()->route('socials')->with('success','Post created successfully');
    }



}
