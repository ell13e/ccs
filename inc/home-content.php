<?php
/**
 * Site copy the client can edit: the homepage, and the template wording on inner pages.
 *
 * Every heading, paragraph and button label on the homepage used to be a
 * string inside a template, so changing one word needed a developer and a
 * deploy. This is the single list of those strings. Each has a key, the
 * Customizer section it belongs to, a label for the editor, and a default (the
 * wording the site launched with), and the templates print them with
 * ccs_copy( 'key' ).
 *
 * Where it is edited: Appearance > Customize > Homepage content (one section per
 * homepage band) and Page wording (one section per inner page), both registered
 * in CCS_Theme_Customizer::register_home_copy(). Inner-page body text is not
 * here: that is editor content, edited on the page itself.
 * Clearing a field restores the default, so a section can never be left blank
 * by accident.
 *
 * Stored as theme mods named ccs_copy_{key}. To make another string editable,
 * add a row here and swap the template's __() call for ccs_copy().
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer sections for homepage copy, in page order.
 *
 * @return array<string,array{panel:string,title:string,description:string}>
 */
function ccs_copy_sections() {
	return array(
		'hero' => array( 'panel' => 'home', 'title' => __( 'Hero', 'ccs-wp-theme' ), 'description' => __( 'The first screen. Keep the heading short: it is the largest text on the site.', 'ccs-wp-theme' ) ),
		'cqc' => array( 'panel' => 'home', 'title' => __( 'CQC rating', 'ccs-wp-theme' ), 'description' => __( 'The sentence citing the Homecare Association figure is fixed in the theme, because it is a sourced claim.', 'ccs-wp-theme' ) ),
		'why' => array( 'panel' => 'home', 'title' => __( 'Why choose us', 'ccs-wp-theme' ), 'description' => '' ),
		'services' => array( 'panel' => 'home', 'title' => __( 'Services', 'ccs-wp-theme' ), 'description' => __( 'The three service cards take their titles and photos from the Services themselves (Services menu). The short text on each card is set here, so it can differ from the summary used on the Services page and in search results.', 'ccs-wp-theme' ) ),
		'story' => array( 'panel' => 'home', 'title' => __( 'Story video', 'ccs-wp-theme' ), 'description' => __( 'The heading above the video. The About page has its own heading for the same video.', 'ccs-wp-theme' ) ),
		'steps' => array( 'panel' => 'home', 'title' => __( 'How care starts', 'ccs-wp-theme' ), 'description' => '' ),
		'diff' => array( 'panel' => 'home', 'title' => __( 'What we do differently', 'ccs-wp-theme' ), 'description' => '' ),
		'costs' => array( 'panel' => 'home', 'title' => __( 'What care costs', 'ccs-wp-theme' ), 'description' => __( 'The rate, its basis, the date and the list of extras are under Care Costs.', 'ccs-wp-theme' ) ),
		'reviews' => array( 'panel' => 'home', 'title' => __( 'Reviews', 'ccs-wp-theme' ), 'description' => __( 'Only use reviews that are published somewhere a reader can check, quoted exactly, with their real date.', 'ccs-wp-theme' ) ),
		'team' => array( 'panel' => 'home', 'title' => __( 'Team', 'ccs-wp-theme' ), 'description' => __( 'The people shown come from the team list.', 'ccs-wp-theme' ) ),
		'news' => array( 'panel' => 'home', 'title' => __( 'Latest articles', 'ccs-wp-theme' ), 'description' => __( 'The three newest posts. Write and edit them under Posts.', 'ccs-wp-theme' ) ),
		'closing' => array( 'panel' => 'home', 'title' => __( 'Closing call to action', 'ccs-wp-theme' ), 'description' => __( 'The button always reads "Book a free consultation", the same as everywhere else on the site.', 'ccs-wp-theme' ) ),

		// Inner pages: the wording that lives in templates rather than in the editor.
		'p_cta' => array( 'panel' => 'pages', 'title' => __( 'Closing band (every page)', 'ccs-wp-theme' ), 'description' => __( 'The dark band at the foot of inner pages. The main button always reads "Book a free consultation".', 'ccs-wp-theme' ) ),
		'p_services' => array( 'panel' => 'pages', 'title' => __( 'Services page', 'ccs-wp-theme' ), 'description' => '' ),
		'p_service' => array( 'panel' => 'pages', 'title' => __( 'Each service page', 'ccs-wp-theme' ), 'description' => __( 'Wording shared by all three service pages. The description, list and questions for each service are edited on the service itself.', 'ccs-wp-theme' ) ),
		'p_areas' => array( 'panel' => 'pages', 'title' => __( 'Areas page', 'ccs-wp-theme' ), 'description' => '' ),
		'p_about' => array( 'panel' => 'pages', 'title' => __( 'About page', 'ccs-wp-theme' ), 'description' => __( 'The main text is edited on the page itself (Pages).', 'ccs-wp-theme' ) ),
		'partners' => array( 'panel' => 'pages', 'title' => __( 'About page: who we work with', 'ccs-wp-theme' ), 'description' => __( 'The heading and introduction above the partner logos.', 'ccs-wp-theme' ) ),
		'p_team' => array( 'panel' => 'pages', 'title' => __( 'Who you will meet', 'ccs-wp-theme' ), 'description' => '' ),
		'p_contact' => array( 'panel' => 'pages', 'title' => __( 'Contact page', 'ccs-wp-theme' ), 'description' => __( 'The three steps under "What happens next" use the wording from Homepage content, How care starts.', 'ccs-wp-theme' ) ),
		'p_faqs' => array( 'panel' => 'pages', 'title' => __( 'FAQs page', 'ccs-wp-theme' ), 'description' => __( 'The questions and answers are edited on the page itself.', 'ccs-wp-theme' ) ),
		'p_cqc' => array( 'panel' => 'pages', 'title' => __( 'CQC page', 'ccs-wp-theme' ), 'description' => '' ),
		'p_careers' => array( 'panel' => 'pages', 'title' => __( 'Careers page', 'ccs-wp-theme' ), 'description' => '' ),
		'p_switch' => array( 'panel' => 'pages', 'title' => __( 'Switching page', 'ccs-wp-theme' ), 'description' => __( 'For families moving their care from another provider. Please check these answers match how you handle a switch.', 'ccs-wp-theme' ) ),
		'p_vac' => array( 'panel' => 'pages', 'title' => __( 'Vacancies page', 'ccs-wp-theme' ), 'description' => __( 'The wording around the CV Minder job portal. The listings themselves come from CV Minder.', 'ccs-wp-theme' ) ),
		'p_404' => array( 'panel' => 'pages', 'title' => __( 'Page not found', 'ccs-wp-theme' ), 'description' => '' ),
	);
}

/**
 * Every editable homepage string.
 *
 * @return array<string,array{section:string,label:string,type:string,default:string}>
 */
function ccs_copy_fields() {
	static $fields = null;

	if ( null !== $fields ) {
		return $fields;
	}

	$fields = array(
		'hero_title' => array( 'section' => 'hero', 'label' => __( 'Main heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Home care in Maidstone & Kent', 'ccs-wp-theme' ) ),
		'hero_promise' => array( 'section' => 'hero', 'label' => __( 'Promise line', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Your team. Your time. Your life.', 'ccs-wp-theme' ) ),
		'cqc_title' => array( 'section' => 'cqc', 'label' => __( 'Heading (put *asterisks* round the word to underline)', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Rated *Good* by the CQC', 'ccs-wp-theme' ) ),
		'why_title' => array( 'section' => 'why', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Why choose us?', 'ccs-wp-theme' ) ),
		'why_subheading' => array( 'section' => 'why', 'label' => __( 'Italic line under the heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Someone who knows how you like things done.', 'ccs-wp-theme' ) ),
		'why_text_1' => array( 'section' => 'why', 'label' => __( 'First paragraph', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Welcoming someone into your home can feel like a big step. Familiar faces help. We plan your care around a small, local team who get to know you, your routine and what matters to you.', 'ccs-wp-theme' ) ),
		'why_text_2' => array( 'section' => 'why', 'label' => __( 'Second paragraph', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'We support adults and children across Maidstone and Kent, day or night.', 'ccs-wp-theme' ) ),
		'why_button_services' => array( 'section' => 'why', 'label' => __( 'First button label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Explore our care services', 'ccs-wp-theme' ) ),
		'services_title' => array( 'section' => 'services', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Home care services in Maidstone and across Kent', 'ccs-wp-theme' ) ),
		'services_card_domiciliary' => array( 'section' => 'services', 'label' => __( 'Domiciliary care card text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'A little help, or more regular support with everyday tasks. Care that respects your routines and helps you keep doing things for yourself.', 'ccs-wp-theme' ) ),
		'services_card_respite' => array( 'section' => 'services', 'label' => __( 'Respite care card text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Time to rest, make plans or simply take a break from caring. Support for a few hours or days, planned around the person you care for.', 'ccs-wp-theme' ) ),
		'services_card_complex' => array( 'section' => 'services', 'label' => __( 'Complex care card text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Support with complex needs, including epilepsy care, PEG and mobility support. A trained team who get to know the person, working alongside their family and healthcare professionals.', 'ccs-wp-theme' ) ),
		'services_cta_line' => array( 'section' => 'services', 'label' => __( 'Question and line under the cards', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Not sure which care you need? You don’t need to know the name of a service. Tell us what’s becoming difficult and what you’d like help with.', 'ccs-wp-theme' ) ),
		'steps_title' => array( 'section' => 'steps', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'How care starts', 'ccs-wp-theme' ) ),
		'steps_lede' => array( 'section' => 'steps', 'label' => __( 'Introduction', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'We aim to arrange a consultation within a few days of your enquiry. Care often begins within a month of agreeing your plan, giving us time to find the right carers for you.', 'ccs-wp-theme' ) ),
		'steps_1_title' => array( 'section' => 'steps', 'label' => __( 'Step 1 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Talk to us', 'ccs-wp-theme' ) ),
		'steps_1_text' => array( 'section' => 'steps', 'label' => __( 'Step 1 text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'A free conversation, by phone or at home, about where you need help and what should stay the same.', 'ccs-wp-theme' ) ),
		'steps_2_title' => array( 'section' => 'steps', 'label' => __( 'Step 2 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Meet your team', 'ccs-wp-theme' ) ),
		'steps_2_text' => array( 'section' => 'steps', 'label' => __( 'Step 2 text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'We find carers who suit your needs and routines, and introduce them before your care begins.', 'ccs-wp-theme' ) ),
		'steps_3_title' => array( 'section' => 'steps', 'label' => __( 'Step 3 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Your care begins', 'ccs-wp-theme' ) ),
		'steps_3_text' => array( 'section' => 'steps', 'label' => __( 'Step 3 text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Your carers follow the plan we’ve agreed with you. We review it with you as your needs change.', 'ccs-wp-theme' ) ),
		'steps_button' => array( 'section' => 'steps', 'label' => __( 'Button label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'See how to get started', 'ccs-wp-theme' ) ),
		'story_title' => array( 'section' => 'story', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Watch our story', 'ccs-wp-theme' ) ),
		'diff_title' => array( 'section' => 'diff', 'label' => __( 'Heading (put *asterisks* round the word to underline)', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'What we do *differently*', 'ccs-wp-theme' ) ),
		'diff_1_title' => array( 'section' => 'diff', 'label' => __( 'Point 1 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'A small team who get to know you', 'ccs-wp-theme' ) ),
		'diff_1_text' => array( 'section' => 'diff', 'label' => __( 'Point 1 text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'It’s easier to feel at home when you know who’s coming. We plan for the same small team to visit regularly, so you can build relationships rather than start again with someone new. We fully fund their training to develop their skills as your needs change. If we don’t have the right match, we can recruit someone to suit your needs.', 'ccs-wp-theme' ) ),
		'diff_2_title' => array( 'section' => 'diff', 'label' => __( 'Point 2 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Your routines, preferences and choices matter', 'ccs-wp-theme' ) ),
		'diff_2_text' => array( 'section' => 'diff', 'label' => __( 'Point 2 text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Care should fit around your life. We talk through what you’d like help with, what you want to keep doing yourself and the routines that matter to you. These shape your care plan, which we review with you as your needs change.', 'ccs-wp-theme' ) ),
		'diff_3_title' => array( 'section' => 'diff', 'label' => __( 'Point 3 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'A local team you can contact', 'ccs-wp-theme' ) ),
		'diff_3_text' => array( 'section' => 'diff', 'label' => __( 'Point 3 text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'When you have a question about care, it helps to know where to turn. Our office team is based at the Maidstone Studios. You can call us Monday to Friday, 9am–5pm, to ask a question or talk through something that’s on your mind.', 'ccs-wp-theme' ) ),
		'diff_4_title' => array( 'section' => 'diff', 'label' => __( 'Point 4 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Knowing what’s happening with your care', 'ccs-wp-theme' ) ),
		'diff_4_text' => array( 'section' => 'diff', 'label' => __( 'Point 4 text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'We agree your care plan with you, so you know what support is planned. As your needs change, we review it together, involving your family and healthcare professionals where appropriate. Your questions and preferences remain part of that conversation.', 'ccs-wp-theme' ) ),
		'costs_title' => array( 'section' => 'costs', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'What care costs', 'ccs-wp-theme' ) ),
		'costs_funding' => array( 'section' => 'costs', 'label' => __( 'Funding sentence', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Care can be funded privately, through local authority support, or through NHS health funding. We’ll talk you through which applies to you.', 'ccs-wp-theme' ) ),
		'costs_quote_note' => array( 'section' => 'costs', 'label' => __( 'Note about personalised quotes', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'These are our published rates, not a quote. Every care plan is different, so after a free consultation we’ll give you a personalised quote for the care you need.', 'ccs-wp-theme' ) ),
		'costs_extras_heading' => array( 'section' => 'costs', 'label' => __( 'Heading above the list of extras', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Also charged', 'ccs-wp-theme' ) ),
		'reviews_title' => array( 'section' => 'reviews', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'What people we support and their families say', 'ccs-wp-theme' ) ),
		'review_1_quote' => array( 'section' => 'reviews', 'label' => __( 'Review 1: quote', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( '…it was the company owner that came out to start doing my care. She delivered my care for the first two weeks and then slowly introduced other carers to me. This helped me immensely as this was the first time I had received care in my home and I was very anxious.', 'ccs-wp-theme' ) ),
		'review_1_author' => array( 'section' => 'reviews', 'label' => __( 'Review 1: name or initials', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'T E', 'ccs-wp-theme' ) ),
		'review_1_relationship' => array( 'section' => 'reviews', 'label' => __( 'Review 1: relationship', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Receives care at home', 'ccs-wp-theme' ) ),
		'review_1_source' => array( 'section' => 'reviews', 'label' => __( 'Review 1: where it was published', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'homecare.co.uk', 'ccs-wp-theme' ) ),
		'review_1_date' => array( 'section' => 'reviews', 'label' => __( 'Review 1: month and year', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'January 2024', 'ccs-wp-theme' ) ),
		'review_2_quote' => array( 'section' => 'reviews', 'label' => __( 'Review 2: quote', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Staff are kind, positive, caring and professional. The owner is strongly committed to providing continuity of care… In my experience with a number of other care companies, they are exceptional.', 'ccs-wp-theme' ) ),
		'review_2_author' => array( 'section' => 'reviews', 'label' => __( 'Review 2: name or initials', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Martin A', 'ccs-wp-theme' ) ),
		'review_2_relationship' => array( 'section' => 'reviews', 'label' => __( 'Review 2: relationship', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Father of someone we support', 'ccs-wp-theme' ) ),
		'review_2_source' => array( 'section' => 'reviews', 'label' => __( 'Review 2: where it was published', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'homecare.co.uk', 'ccs-wp-theme' ) ),
		'review_2_date' => array( 'section' => 'reviews', 'label' => __( 'Review 2: month and year', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'November 2023', 'ccs-wp-theme' ) ),
		'review_3_quote' => array( 'section' => 'reviews', 'label' => __( 'Review 3: quote', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'The care my sister has received from Continuity Care over the last few years has been exceptional. I have now had the pleasure of meeting a few of the carers at J’s home and find them cheerful, caring, and professional… I know J is happy and well cared for, and she looks forward to seeing them.', 'ccs-wp-theme' ) ),
		'review_3_author' => array( 'section' => 'reviews', 'label' => __( 'Review 3: name or initials', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Family member', 'ccs-wp-theme' ) ),
		'review_3_relationship' => array( 'section' => 'reviews', 'label' => __( 'Review 3: relationship', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Sister of someone we support', 'ccs-wp-theme' ) ),
		'review_3_source' => array( 'section' => 'reviews', 'label' => __( 'Review 3: where it was published', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Facebook', 'ccs-wp-theme' ) ),
		'review_3_date' => array( 'section' => 'reviews', 'label' => __( 'Review 3: month and year', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'October 2024', 'ccs-wp-theme' ) ),
		'reviews_button' => array( 'section' => 'reviews', 'label' => __( 'Button label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Read more reviews on homecare.co.uk', 'ccs-wp-theme' ) ),
		'team_title' => array( 'section' => 'team', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Meet the people behind your care', 'ccs-wp-theme' ) ),
		'team_button' => array( 'section' => 'team', 'label' => __( 'Button label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Meet the whole team', 'ccs-wp-theme' ) ),
		'partners_title' => array( 'section' => 'partners', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Who we work with', 'ccs-wp-theme' ) ),
		'news_title' => array( 'section' => 'news', 'label' => __( 'Heading (put *asterisks* round the word to underline)', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Advice from *our team*', 'ccs-wp-theme' ) ),
		'news_button' => array( 'section' => 'news', 'label' => __( 'Link to all articles', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'See all articles', 'ccs-wp-theme' ) ),
		'closing_title' => array( 'section' => 'closing', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'You don’t need to have it all worked out.', 'ccs-wp-theme' ) ),
		'closing_text' => array( 'section' => 'closing', 'label' => __( 'First paragraph', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'You might be looking for yourself, helping someone close to you, or wondering whether it’s time for more support. You don’t have to make that decision before speaking to us.', 'ccs-wp-theme' ) ),
		'closing_text_2' => array( 'section' => 'closing', 'label' => __( 'Second paragraph', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'We’ll listen, answer your questions and talk through the options. The conversation is free, with nothing to sign and no commitment to arrange care.', 'ccs-wp-theme' ) ),

		// Inner pages.
		'cta_title' => array( 'section' => 'p_cta', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'You don’t need to have it all worked out.', 'ccs-wp-theme' ) ),
		'cta_text' => array( 'section' => 'p_cta', 'label' => __( 'Text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( "You might be looking for care for yourself or someone close to you. If you’re unsure what support you need, or whether it’s time for care, we can talk it through.\n\nWe’ll listen, answer your questions and explain the options. It’s a free conversation, with nothing to sign and no commitment to arrange care.", 'ccs-wp-theme' ) ),
		'cta_careers_title' => array( 'section' => 'p_cta', 'label' => __( 'Careers pages: heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Fancy joining us?', 'ccs-wp-theme' ) ),
		'cta_careers_text' => array( 'section' => 'p_cta', 'label' => __( 'Careers pages: text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'We are always glad to hear from people who want to do this job well. See the roles we are recruiting for now.', 'ccs-wp-theme' ) ),
		'cta_careers_button' => array( 'section' => 'p_cta', 'label' => __( 'Careers pages: button label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'See current vacancies', 'ccs-wp-theme' ) ),
		'contact_title' => array( 'section' => 'p_contact', 'label' => __( 'Page heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Book your free care consultation', 'ccs-wp-theme' ) ),
		'contact_email_note' => array( 'section' => 'p_contact', 'label' => __( 'Note under the email address', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'For anything that does not need an answer today.', 'ccs-wp-theme' ) ),
		'contact_form_title' => array( 'section' => 'p_contact', 'label' => __( 'Form heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Tell us what you *need*', 'ccs-wp-theme' ) ),
		'contact_talk_title' => array( 'section' => 'p_contact', 'label' => __( 'Call card heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Prefer to *talk*?', 'ccs-wp-theme' ) ),
		'contact_next_title' => array( 'section' => 'p_contact', 'label' => __( 'Heading above the three steps', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'What happens *next*', 'ccs-wp-theme' ) ),
		'service_related_title' => array( 'section' => 'p_service', 'label' => __( 'Heading (put *asterisks* round the word to underline)', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Other ways we can *help*', 'ccs-wp-theme' ) ),
		'service_included_title' => array( 'section' => 'p_service', 'label' => __( 'Heading above the list of what is included', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'What’s included', 'ccs-wp-theme' ) ),
		'service_faq_title' => array( 'section' => 'p_service', 'label' => __( 'Questions heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Questions families ask', 'ccs-wp-theme' ) ),
		'service_callback_title' => array( 'section' => 'p_service', 'label' => __( 'Call-back form heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Ask us to call you back', 'ccs-wp-theme' ) ),
		'service_hub_label' => array( 'section' => 'p_service', 'label' => __( 'All-services card: small label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'All services', 'ccs-wp-theme' ) ),
		'service_hub_title' => array( 'section' => 'p_service', 'label' => __( 'All-services card: title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Everything we offer at home', 'ccs-wp-theme' ) ),
		'service_hub_text' => array( 'section' => 'p_service', 'label' => __( 'All-services card: text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'See every kind of care we provide across Maidstone and Kent, and who each one is for.', 'ccs-wp-theme' ) ),
		'service_hub_link' => array( 'section' => 'p_service', 'label' => __( 'All-services card: link text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'See all services', 'ccs-wp-theme' ) ),
		'areas_title' => array( 'section' => 'p_areas', 'label' => __( 'Page heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Areas we cover', 'ccs-wp-theme' ) ),
		'areas_lede' => array( 'section' => 'p_areas', 'label' => __( 'Introduction', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Home care across Maidstone and the Kent towns around it. Choose your area to see the villages we visit and the care we provide there.', 'ccs-wp-theme' ) ),
		'areas_strip_title' => array( 'section' => 'p_areas', 'label' => __( '"Not on the list" heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Not on the *list*?', 'ccs-wp-theme' ) ),
		'areas_strip_text' => array( 'section' => 'p_areas', 'label' => __( '"Not on the list" text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'We may still be able to help. Call us and we will tell you whether we can cover where you live.', 'ccs-wp-theme' ) ),
		'careers_expect_title' => array( 'section' => 'p_careers', 'label' => __( 'Heading (put *asterisks* round the word to underline)', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Care work, *done* well', 'ccs-wp-theme' ) ),
		'careers_ways_title' => array( 'section' => 'p_careers', 'label' => __( 'Heading above the three cards', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Find your *way in*', 'ccs-wp-theme' ) ),
		'working_intro_title' => array( 'section' => 'p_careers', 'label' => __( 'Working with us: intro heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Your small, *friendly* team', 'ccs-wp-theme' ) ),
		'working_tabs_title' => array( 'section' => 'p_careers', 'label' => __( 'Working with us: heading over the tabs', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Good to know *before* you apply', 'ccs-wp-theme' ) ),
		'careers_benefits_title' => array( 'section' => 'p_careers', 'label' => __( 'Benefits: heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Your *benefits*', 'ccs-wp-theme' ) ),
		'careers_benefits' => array( 'section' => 'p_careers', 'label' => __( 'Benefits: one group per line, as Group | benefit; benefit; benefit', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( "Pay and time | Higher than average rates of pay; Enhanced pay on Saturdays, Sundays and bank holidays; Flexible working hours and local work; Pension scheme with The People’s Pension; Annual saving scheme\nStarting out | Fully funded and paid training; Access to our training academy; Meet and greets and shadow shifts to help you settle in; DBS assistance\nGrowing with us | Health and social care qualifications; Career progression\nSupport | 24/7 counselling and support; Mental Health First Aiders to speak to; Smart Clinic, our Employee Assistance Programme\nExtras | Blue Light Card, subscription paid by us; Company phone; Annual charity events", 'ccs-wp-theme' ) ),
		'careers_benefits_referral' => array( 'section' => 'p_careers', 'label' => __( 'Benefits: referral scheme, as Figure | Name | Small print', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( '£500 | Refer a friend scheme | Terms and conditions apply', 'ccs-wp-theme' ) ),
		'careers_proof_title' => array( 'section' => 'p_careers', 'label' => __( 'New starters: heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'From our *newest* team members', 'ccs-wp-theme' ) ),
		'careers_proof_source' => array( 'section' => 'p_careers', 'label' => __( 'New starters: where the figures come from', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'From our own onboarding feedback form, answered by 25 new starters. Comments are in their own words.', 'ccs-wp-theme' ) ),
		'careers_proof_stats' => array( 'section' => 'p_careers', 'label' => __( 'New starters: the figure on the photo, as Figure | What it counts | Small print', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( "4.92 | out of 5 for recruitment and onboarding | Rated by 25 new starters", 'ccs-wp-theme' ) ),
		'careers_proof_quotes' => array( 'section' => 'p_careers', 'label' => __( 'New starters: comments, one per line, as Comment | Who said it (use whole sentences)', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( "I have enjoyed the training and the company is very welcoming | New starter, Care Assistant\nExcellent communication and guidance throughout the whole process. | New starter, Complex Care Assistant\nThe trainer was very informative and the people on the course were lovely. | New starter, Care Assistant\nI thank the team for being so kind, flexible and welcoming. | New starter, Complex Care Assistant", 'ccs-wp-theme' ) ),
		'pd_tabs_title' => array( 'section' => 'p_careers', 'label' => __( 'Professional Development: heading over the tabs', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'How we train and *support* you', 'ccs-wp-theme' ) ),
		'pd_academy_title' => array( 'section' => 'p_careers', 'label' => __( 'Development: training academy heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Our own training *academy*', 'ccs-wp-theme' ) ),
		'pd_academy_text' => array( 'section' => 'p_careers', 'label' => __( 'Development: training academy text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Your training is fully funded and paid, and you get access to Continuity Training Academy, our sister training company at The Maidstone Studios.', 'ccs-wp-theme' ) ),
		'pd_academy_badge' => array( 'section' => 'p_careers', 'label' => __( 'Development: tab on the academy photo, as Figure | Name | Small print', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( '40+ | CPD-accredited courses | at Continuity Training Academy', 'ccs-wp-theme' ) ),
		'careers_card_1_label' => array( 'section' => 'p_careers', 'label' => __( 'Card 1: small label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Apply', 'ccs-wp-theme' ) ),
		'careers_card_1_text' => array( 'section' => 'p_careers', 'label' => __( 'Card 1: text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'The roles we are recruiting for now, and how to apply.', 'ccs-wp-theme' ) ),
		'careers_card_1_link' => array( 'section' => 'p_careers', 'label' => __( 'Card 1: link text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'See vacancies', 'ccs-wp-theme' ) ),
		'careers_card_2_label' => array( 'section' => 'p_careers', 'label' => __( 'Card 2: small label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Day to day', 'ccs-wp-theme' ) ),
		'careers_card_2_text' => array( 'section' => 'p_careers', 'label' => __( 'Card 2: text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Pay, rotas and the support you can expect once you join.', 'ccs-wp-theme' ) ),
		'careers_card_2_link' => array( 'section' => 'p_careers', 'label' => __( 'Card 2: link text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Read more', 'ccs-wp-theme' ) ),
		'careers_card_3_label' => array( 'section' => 'p_careers', 'label' => __( 'Card 3: small label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Training', 'ccs-wp-theme' ) ),
		'careers_card_3_text' => array( 'section' => 'p_careers', 'label' => __( 'Card 3: text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Funded, paid training, from the Care Certificate to specialist clinical skills.', 'ccs-wp-theme' ) ),
		'careers_card_3_link' => array( 'section' => 'p_careers', 'label' => __( 'Card 3: link text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Read more', 'ccs-wp-theme' ) ),
		'team_page_intro_title' => array( 'section' => 'p_team', 'label' => __( 'Heading (put *asterisks* round the word to underline)', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Know who you are *talking* to', 'ccs-wp-theme' ) ),
		'team_page_title' => array( 'section' => 'p_team', 'label' => __( 'Team heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Say *hello* to the team', 'ccs-wp-theme' ) ),
		'team_page_strip_title' => array( 'section' => 'p_team', 'label' => __( 'Careers prompt: heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Want to *work* with us?', 'ccs-wp-theme' ) ),
		'team_page_strip_text' => array( 'section' => 'p_team', 'label' => __( 'Careers prompt: text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'We are always glad to hear from kind, reliable people who want to care well.', 'ccs-wp-theme' ) ),
		'team_page_strip_button' => array( 'section' => 'p_team', 'label' => __( 'Careers prompt: button label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'See careers', 'ccs-wp-theme' ) ),
		'faqs_title' => array( 'section' => 'p_faqs', 'label' => __( 'Heading (put *asterisks* round the word to underline)', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Questions about care at *home*', 'ccs-wp-theme' ) ),
		'faqs_box_title' => array( 'section' => 'p_faqs', 'label' => __( 'Help box heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Question not here?', 'ccs-wp-theme' ) ),
		'about_intro_title' => array( 'section' => 'p_about', 'label' => __( 'Heading (put *asterisks* round the word to underline)', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Care that *fits* around your life', 'ccs-wp-theme' ) ),
		'about_story_title' => array( 'section' => 'p_about', 'label' => __( 'Video section heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Where it *started*', 'ccs-wp-theme' ) ),
		'about_story_text' => array( 'section' => 'p_about', 'label' => __( 'Video section text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Our company director and registered manager, Victoria Walker, explains how Continuity of Care started here in Kent and what it set out to do differently.', 'ccs-wp-theme' ) ),
		'about_journey_title' => array( 'section' => 'p_about', 'label' => __( 'Journey: heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Our *story* so far', 'ccs-wp-theme' ) ),
		'about_journey' => array( 'section' => 'p_about', 'label' => __( 'Journey: one milestone per line, as Year | Heading | Text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( "2016 | Where it began | Victoria Walker founded Continuity of Care Services in Maidstone, Kent.\n2017 | Our first CQC inspection | We grew our team, built out specialist departments and received our first CQC inspection, rated Good.\n2018 | Recognised in Kent | We were a finalist at the Kent Excellence in Business Awards, placed second in the Kent Women in Business start-up category, and became a gold sponsor of Music on the Green.\n2019 | Looking after our own team | Our HR and Compliance Manager was a runner-up at the Kent Women in Business Awards, and we appointed a Mental Health First Aider to support staff.\n2020 | Continuity Training Academy | We founded Continuity Training Academy. In a challenging year we supported the NHS and raised £545 for charity.\n2021 | Good again, and nursing care | The CQC confirmed our Good rating after re-inspection. We extended our registration to provide nursing care, and our general manager was a finalist in two Kent Women in Business categories.\n2022 | Foundations for growth | We restructured the company, giving every department a stronger footing for what came next.\n2023 | Complex care grows | Our complex care division expanded significantly. The CQC inspected us in October and rated us Good.\n2024 | Across Kent | Our complex care division doubled in size. We recruited more specialist staff across Kent while keeping care safe and person-centred.\n2025 | A bigger team | We expanded our management team and took on more domiciliary care for people living independently. Continuity Training Academy widened its courses and took part in the Care Show in April.\n2026 | Ten years | We marked our tenth anniversary, launched Restwell Retreats, our accessible holiday home in Whitstable, and expanded our CQC registration to include supported living.", 'ccs-wp-theme' ) ),
		'about_family_title' => array( 'section' => 'p_about', 'label' => __( 'Sister companies: heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'The Continuity *family*', 'ccs-wp-theme' ) ),
		'about_family_cta' => array( 'section' => 'p_about', 'label' => __( 'Continuity Training Academy: text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Our training company, founded in 2020. It runs face-to-face, CPD-accredited courses for care teams, at The Maidstone Studios or at their own workplace, and we work in partnership with it to train our own carers.', 'ccs-wp-theme' ) ),
		'about_family_restwell' => array( 'section' => 'p_about', 'label' => __( 'Restwell Retreats: text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Our accessible holiday home in Whitstable, opened in 2026. A private, single-storey bungalow a short walk from the sea, with a level wet room and a ceiling track hoist already fitted. Guests can add home care from us during their stay.', 'ccs-wp-theme' ) ),
		'about_family_cta_facts' => array( 'section' => 'p_about', 'label' => __( 'Continuity Training Academy: facts, as Figure | Label; separated by semicolons', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( '4.8/5 | rated Excellent on Trustpilot; 40+ | CPD-accredited courses; 2,000+ | care professionals trained', 'ccs-wp-theme' ) ),
		'about_family_restwell_facts' => array( 'section' => 'p_about', 'label' => __( 'Restwell Retreats: facts, as Figure | Label; separated by semicolons', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( '5 stars | on Google reviews; Sleeps 5 | across two bedrooms and a sofa bed; Step-free | throughout the bungalow', 'ccs-wp-theme' ) ),
		'services_page_promise_title' => array( 'section' => 'p_services', 'label' => __( 'Second section heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Whichever service, the same *promise*', 'ccs-wp-theme' ) ),
		'cqc_page_title' => array( 'section' => 'p_cqc', 'label' => __( 'Intro heading (put *asterisks* round the word to underline)', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Care you can *check* for yourself', 'ccs-wp-theme' ) ),
		'e404_title' => array( 'section' => 'p_404', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'That page isn’t here any more', 'ccs-wp-theme' ) ),
		'e404_text' => array( 'section' => 'p_404', 'label' => __( 'Text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'The link may be out of date, or the address may have a typo in it. Nothing is wrong at your end. These are the pages most people are looking for.', 'ccs-wp-theme' ) ),
		'e404_phone_line' => array( 'section' => 'p_404', 'label' => __( 'Words before the phone number', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'If it’s easier to just ask someone, call', 'ccs-wp-theme' ) ),
		'e404_link_1_title' => array( 'section' => 'p_404', 'label' => __( 'Link 1: title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Our care services', 'ccs-wp-theme' ) ),
		'e404_link_1_text' => array( 'section' => 'p_404', 'label' => __( 'Link 1: text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Domiciliary, respite and complex care at home.', 'ccs-wp-theme' ) ),
		'e404_link_2_title' => array( 'section' => 'p_404', 'label' => __( 'Link 2: title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Arrange a conversation', 'ccs-wp-theme' ) ),
		'e404_link_2_text' => array( 'section' => 'p_404', 'label' => __( 'Link 2: text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Talk it through with someone local. No commitment.', 'ccs-wp-theme' ) ),
		'e404_link_3_title' => array( 'section' => 'p_404', 'label' => __( 'Link 3: title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Getting started', 'ccs-wp-theme' ) ),
		'e404_link_3_text' => array( 'section' => 'p_404', 'label' => __( 'Link 3: text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'What happens between first call and first visit.', 'ccs-wp-theme' ) ),
		'e404_link_4_title' => array( 'section' => 'p_404', 'label' => __( 'Link 4: title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Common questions', 'ccs-wp-theme' ) ),
		'e404_link_4_text' => array( 'section' => 'p_404', 'label' => __( 'Link 4: text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Costs, timescales, and who your carers would be.', 'ccs-wp-theme' ) ),
		'e404_search_title' => array( 'section' => 'p_404', 'label' => __( 'Search heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Or search the site', 'ccs-wp-theme' ) ),
		// Switching page.
		'switch_title' => array( 'section' => 'p_switch', 'label' => __( 'Page heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Switching your home care to us', 'ccs-wp-theme' ) ),
		'switch_intro' => array( 'section' => 'p_switch', 'label' => __( 'Introduction', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'If your current care isn’t working, you can change provider. We plan the move with you around the date your current care ends, and we take care of the handover.', 'ccs-wp-theme' ) ),
		'switch_button' => array( 'section' => 'p_switch', 'label' => __( 'Main button label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Start my switch', 'ccs-wp-theme' ) ),
		'switch_steps_title' => array( 'section' => 'p_switch', 'label' => __( 'Steps heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'How *switching* works', 'ccs-wp-theme' ) ),
		'switch_step_1_title' => array( 'section' => 'p_switch', 'label' => __( 'Step 1 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Tell us what is not working', 'ccs-wp-theme' ) ),
		'switch_step_1_text' => array( 'section' => 'p_switch', 'label' => __( 'Step 1 text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'A free, no-obligation conversation with our Maidstone office. You do not need to have given notice yet.', 'ccs-wp-theme' ) ),
		'switch_step_2_title' => array( 'section' => 'p_switch', 'label' => __( 'Step 2 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'We plan the handover', 'ccs-wp-theme' ) ),
		'switch_step_2_text' => array( 'section' => 'p_switch', 'label' => __( 'Step 2 text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'We agree a start date that follows on from your current care, and meet you to write your care plan before anyone visits.', 'ccs-wp-theme' ) ),
		'switch_step_3_title' => array( 'section' => 'p_switch', 'label' => __( 'Step 3 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Your new team starts', 'ccs-wp-theme' ) ),
		'switch_step_3_text' => array( 'section' => 'p_switch', 'label' => __( 'Step 3 text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'A senior carer works alongside your new team for the first visits, so the handover happens in person.', 'ccs-wp-theme' ) ),
		'switch_faqs_title' => array( 'section' => 'p_switch', 'label' => __( 'Questions heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Questions families ask *before* switching', 'ccs-wp-theme' ) ),
		'switch_faq_1_q' => array( 'section' => 'p_switch', 'label' => __( 'Question 1', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Do I need to give notice to my current provider first?', 'ccs-wp-theme' ) ),
		'switch_faq_1_a' => array( 'section' => 'p_switch', 'label' => __( 'Answer 1', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Not before you talk to us. Check your contract for the notice period, and we will plan our start date around it.', 'ccs-wp-theme' ) ),
		'switch_faq_2_q' => array( 'section' => 'p_switch', 'label' => __( 'Question 2', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Will there be a gap in care?', 'ccs-wp-theme' ) ),
		'switch_faq_2_a' => array( 'section' => 'p_switch', 'label' => __( 'Answer 2', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'We agree the start date with you before anything changes, so your care carries on while it moves to us.', 'ccs-wp-theme' ) ),
		'switch_faq_3_q' => array( 'section' => 'p_switch', 'label' => __( 'Question 3', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'What if my care is arranged by the council?', 'ccs-wp-theme' ) ),
		'switch_faq_3_a' => array( 'section' => 'p_switch', 'label' => __( 'Answer 3', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Tell us when you call. If Kent County Council or the NHS arranges your care, the change goes through them, and we will talk you through what that involves.', 'ccs-wp-theme' ) ),
		'switch_form_title' => array( 'section' => 'p_switch', 'label' => __( 'Form heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( '*Ask* about switching', 'ccs-wp-theme' ) ),
		'switch_form_text' => array( 'section' => 'p_switch', 'label' => __( 'Form introduction', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Your name and a phone number are all we need. We will call you back to talk it through.', 'ccs-wp-theme' ) ),
		'switch_call_text' => array( 'section' => 'p_switch', 'label' => __( 'Call card text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Switching can feel like a lot to put in writing. Ring our office team and we will talk it through with you.', 'ccs-wp-theme' ) ),
		// Vacancies page.
		'vac_steps_title' => array( 'section' => 'p_vac', 'label' => __( 'Steps heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Your next *steps*', 'ccs-wp-theme' ) ),
		'vac_step_1_title' => array( 'section' => 'p_vac', 'label' => __( 'Step 1 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'We read every application', 'ccs-wp-theme' ) ),
		'vac_step_1_text' => array( 'section' => 'p_vac', 'label' => __( 'Step 1 text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Someone in our Maidstone office reads it and gets back to you.', 'ccs-wp-theme' ) ),
		'vac_step_2_title' => array( 'section' => 'p_vac', 'label' => __( 'Step 2 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'An informal chat', 'ccs-wp-theme' ) ),
		'vac_step_2_text' => array( 'section' => 'p_vac', 'label' => __( 'Step 2 text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'About the role, the hours you want and the area you can cover.', 'ccs-wp-theme' ) ),
		'vac_step_3_title' => array( 'section' => 'p_vac', 'label' => __( 'Step 3 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Checks and training', 'ccs-wp-theme' ) ),
		'vac_step_3_text' => array( 'section' => 'p_vac', 'label' => __( 'Step 3 text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'An enhanced DBS check, which we pay for, then the Care Certificate and shadowing before you work alone.', 'ccs-wp-theme' ) ),
		'vac_portal_title' => array( 'section' => 'p_vac', 'label' => __( 'Heading above the job portal', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Open roles', 'ccs-wp-theme' ) ),
	);

	return $fields;
}

/**
 * One piece of homepage copy: the client's wording, or the default.
 *
 * Returns raw text. Escape at output: esc_html( ccs_copy( 'key' ) ).
 *
 * @param string $key Field key from ccs_copy_fields().
 * @return string
 */
function ccs_copy( $key ) {
	$fields  = ccs_copy_fields();
	$default = isset( $fields[ $key ] ) ? $fields[ $key ]['default'] : '';
	$value   = get_theme_mod( 'ccs_copy_' . $key, '' );

	// Empty means "use the default": a cleared field should not blank a heading.
	return ( is_string( $value ) && '' !== trim( $value ) ) ? $value : $default;
}

/**
 * A heading whose emphasised word is marked with asterisks.
 *
 * "Rated *Good* by the CQC" prints the heading with the brush underline on
 * "Good". Asterisks are something a non-technical editor can type; the old
 * printf( '%s' ) placeholder was not. Only the first marked phrase is
 * underlined (CLAUDE.md rule 12: one word that matters), and a heading with no
 * asterisks simply prints plain.
 *
 * @param string $key Field key from ccs_copy_fields().
 * @return string Escaped HTML.
 */
function ccs_copy_accent( $key ) {
	return ccs_accent_text( ccs_copy( $key ) );
}

/**
 * Escape a heading and set its *starred* word as the accent. For headings
 * that reach a template as plain text (handed on as an argument) rather than
 * by copy key.
 *
 * @param string $text Heading text, with one word or phrase in asterisks.
 * @return string Escaped HTML.
 */
function ccs_accent_text( $text ) {
	$text = (string) $text;

	if ( preg_match( '/^(.*?)\*([^*]+)\*(.*)$/s', $text, $m ) ) {
		return esc_html( $m[1] ) . ccs_accent( $m[2] ) . esc_html( str_replace( '*', '', $m[3] ) );
	}

	return esc_html( $text );
}
