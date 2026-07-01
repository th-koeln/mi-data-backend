<?php

function abschlussarbeit_to_array(Kirby\Cms\Page $page): array
{
    $opt = fn($field) => $field->isNotEmpty() ? $field->value() : null;

    $keywords = null;
    if ($page->keywords()->isNotEmpty()) {
        $parts = array_map('trim', explode(',', $page->keywords()->value()));
        $keywords = array_values(array_filter($parts));
    }

    $awards = null;
    if ($page->awards()->isNotEmpty()) {
        $awards = [];
        foreach ($page->awards()->toStructure() as $item) {
            $text = $item->award()->value();
            if ($text) {
                $awards[] = $text;
            }
        }
        if (empty($awards)) {
            $awards = null;
        }
    }

    $socialUrls = null;
    if ($page->personal_social_media_urls()->isNotEmpty()) {
        $socialUrls = [];
        foreach ($page->personal_social_media_urls()->toStructure() as $item) {
            $url = $item->url()->value();
            if ($url) {
                $socialUrls[] = $url;
            }
        }
        if (empty($socialUrls)) {
            $socialUrls = null;
        }
    }

    // Pflichtfelder sind immer gesetzt
    $data = [
        'title'             => $page->title()->value(),
        'type'              => $page->worktype()->value(),
        'date'              => $page->date()->toDate('Y-m-d'),
        'status'            => $page->workstatus()->value(),
        'visibility'        => $page->isListed() ? 'published' : 'unpublished',
        'firstname'         => $page->firstname()->value(),
        'lastname'          => $page->lastname()->value(),
        'first_supervisor'  => $page->first_supervisor()->value(),
        'second_supervisor' => $page->second_supervisor()->value(),
        'slideshow'         => $page->slideshow()->toBool(),
        'research_diary'    => $page->research_diary()->toBool(),
    ];

    // Optionale Felder: nur einschließen wenn belegt
    $optional = [
        'abstract'                      => $opt($page->abstract()),
        'keywords'                      => $keywords,
        'thesis_url'                    => $opt($page->thesis_url()),
        'teaser_image_url'              => $opt($page->teaser_image_url()),
        'teaser_image_copyright'        => $opt($page->teaser_image_copyright()),
        'avatar_url'                    => $opt($page->avatar_url()),
        'repository_url'                => $opt($page->repository_url()),
        'project_url'                   => $opt($page->project_url()),
        'final_presentation_youtube_id' => $opt($page->final_presentation_youtube_id()),
        'awards'                => $awards,
        'related_folder'                => $opt($page->related_folder()),
        'personal_website_url'          => $opt($page->personal_website_url()),
        'personal_social_media_urls'    => $socialUrls,
        'cooperation_partner'           => $opt($page->cooperation_partner()),
        'cooperation_partner_url'       => $opt($page->cooperation_partner_url()),
    ];

    foreach ($optional as $key => $value) {
        if ($value !== null) {
            $data[$key] = $value;
        }
    }

    return $data;
}
