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
		'services' => array( 'panel' => 'home', 'title' => __( 'Services', 'ccs-wp-theme' ), 'description' => __( 'The three service cards take their titles, text and photos from the Services themselves (Services menu).', 'ccs-wp-theme' ) ),
		'steps' => array( 'panel' => 'home', 'title' => __( 'How care starts', 'ccs-wp-theme' ), 'description' => '' ),
		'diff' => array( 'panel' => 'home', 'title' => __( 'What we do differently', 'ccs-wp-theme' ), 'description' => '' ),
		'costs' => array( 'panel' => 'home', 'title' => __( 'What care costs', 'ccs-wp-theme' ), 'description' => __( 'The rate, its basis, the date and the list of extras are under Care Costs.', 'ccs-wp-theme' ) ),
		'reviews' => array( 'panel' => 'home', 'title' => __( 'Reviews', 'ccs-wp-theme' ), 'description' => __( 'Only use reviews that are published somewhere a reader can check, quoted exactly, with their real date.', 'ccs-wp-theme' ) ),
		'team' => array( 'panel' => 'home', 'title' => __( 'Team', 'ccs-wp-theme' ), 'description' => __( 'The people shown come from the team list.', 'ccs-wp-theme' ) ),
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
		'hero_support' => array( 'section' => 'hero', 'label' => __( 'Supporting sentence', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'From everyday help at home to complex and specialist care, we match you with a small team of carers you’ll get to know.', 'ccs-wp-theme' ) ),
		'hero_support_extra' => array( 'section' => 'hero', 'label' => __( 'Second sentence (hidden on small phones)', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'You’ll see the same faces at every visit.', 'ccs-wp-theme' ) ),
		'cqc_eyebrow' => array( 'section' => 'cqc', 'label' => __( 'Label above the heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Independently regulated', 'ccs-wp-theme' ) ),
		'cqc_title' => array( 'section' => 'cqc', 'label' => __( 'Heading (put *asterisks* round the word to underline)', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Rated *Good* by the CQC', 'ccs-wp-theme' ) ),
		'cqc_button' => array( 'section' => 'cqc', 'label' => __( 'Button label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Read our full CQC report', 'ccs-wp-theme' ) ),
		'why_title' => array( 'section' => 'why', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Why Choose Us for Home Care in Maidstone?', 'ccs-wp-theme' ) ),
		'why_subheading' => array( 'section' => 'why', 'label' => __( 'Italic line under the heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'The difference is who turns up, and how often it’s the same person.', 'ccs-wp-theme' ) ),
		'why_text_1' => array( 'section' => 'why', 'label' => __( 'First paragraph', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'We support adults and children across Maidstone and the rest of Kent, day or night. Our carers live locally, and the same small team comes back each visit.', 'ccs-wp-theme' ) ),
		'why_text_2' => array( 'section' => 'why', 'label' => __( 'First paragraph, closing sentence', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'We don’t rush, and we don’t rotate staff every other week.', 'ccs-wp-theme' ) ),
		'why_proof_1' => array( 'section' => 'why', 'label' => __( 'Proof point 1', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Rated Good by the CQC', 'ccs-wp-theme' ) ),
		'why_proof_2' => array( 'section' => 'why', 'label' => __( 'Proof point 2', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( '10+ years caring for Kent families', 'ccs-wp-theme' ) ),
		'why_proof_3' => array( 'section' => 'why', 'label' => __( 'Proof point 3', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Adults and children supported', 'ccs-wp-theme' ) ),
		'why_proof_4' => array( 'section' => 'why', 'label' => __( 'Proof point 4', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Care day or night, across Kent', 'ccs-wp-theme' ) ),
		'why_button_services' => array( 'section' => 'why', 'label' => __( 'First button label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Learn more about our services', 'ccs-wp-theme' ) ),
		'services_title' => array( 'section' => 'services', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Home care services in Maidstone and across Kent', 'ccs-wp-theme' ) ),
		'services_lede' => array( 'section' => 'services', 'label' => __( 'Introduction', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Some people need a hand for an hour in the morning. Others need someone there through the night, or a team trained for more complex health needs. We’ll work out which it is with you, before anything is agreed.', 'ccs-wp-theme' ) ),
		'services_cta_line' => array( 'section' => 'services', 'label' => __( 'Line above the buttons', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Tell us what’s happening and we’ll say honestly whether we can help.', 'ccs-wp-theme' ) ),
		'services_button_all' => array( 'section' => 'services', 'label' => __( 'Second button label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'See all our services', 'ccs-wp-theme' ) ),
		'steps_title' => array( 'section' => 'steps', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'How care starts', 'ccs-wp-theme' ) ),
		'steps_lede' => array( 'section' => 'steps', 'label' => __( 'Introduction', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'We aim to arrange a consultation within a few days of your enquiry, and can often start within one to two weeks of agreeing a care plan.', 'ccs-wp-theme' ) ),
		'steps_1_title' => array( 'section' => 'steps', 'label' => __( 'Step 1 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Talk to us', 'ccs-wp-theme' ) ),
		'steps_1_text' => array( 'section' => 'steps', 'label' => __( 'Step 1 text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'A free consultation at your pace, on the phone or at the kitchen table.', 'ccs-wp-theme' ) ),
		'steps_2_title' => array( 'section' => 'steps', 'label' => __( 'Step 2 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Meet your team', 'ccs-wp-theme' ) ),
		'steps_2_text' => array( 'section' => 'steps', 'label' => __( 'Step 2 text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'We match carers to the person, not to a rota.', 'ccs-wp-theme' ) ),
		'steps_3_title' => array( 'section' => 'steps', 'label' => __( 'Step 3 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Care begins calmly', 'ccs-wp-theme' ) ),
		'steps_3_text' => array( 'section' => 'steps', 'label' => __( 'Step 3 text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Planned, unhurried and reviewed as needs change.', 'ccs-wp-theme' ) ),
		'steps_button' => array( 'section' => 'steps', 'label' => __( 'Button label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'See the full process', 'ccs-wp-theme' ) ),
		'diff_title' => array( 'section' => 'diff', 'label' => __( 'Heading (put *asterisks* round the word to underline)', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'What we do *differently*', 'ccs-wp-theme' ) ),
		'diff_lede' => array( 'section' => 'diff', 'label' => __( 'Introduction', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Every provider says they care. These are the four things you can hold us to.', 'ccs-wp-theme' ) ),
		'diff_1_title' => array( 'section' => 'diff', 'label' => __( 'Point 1 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'A team matched to you, not whoever is free', 'ccs-wp-theme' ) ),
		'diff_1_text' => array( 'section' => 'diff', 'label' => __( 'Point 1 text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'The same small team visits you, so you build a relationship and never have to keep explaining your needs. If nobody in our current team fits your hours, your home, or simply doesn’t get on with you, we recruit someone specifically for you.', 'ccs-wp-theme' ) ),
		'diff_2_title' => array( 'section' => 'diff', 'label' => __( 'Point 2 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Care plans built around you', 'ccs-wp-theme' ) ),
		'diff_2_text' => array( 'section' => 'diff', 'label' => __( 'Point 2 text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Your care plan starts with how you actually live, not a one-size-fits-all checklist. We review it regularly and adjust as your situation changes.', 'ccs-wp-theme' ) ),
		'diff_3_title' => array( 'section' => 'diff', 'label' => __( 'Point 3 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Local, and quick to respond', 'ccs-wp-theme' ) ),
		'diff_3_text' => array( 'section' => 'diff', 'label' => __( 'Point 3 text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'We’re based in Maidstone and work across Kent, so our teams already know your area and can get to you quickly.', 'ccs-wp-theme' ) ),
		'diff_4_title' => array( 'section' => 'diff', 'label' => __( 'Point 4 title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'You are kept in the loop', 'ccs-wp-theme' ) ),
		'diff_4_text' => array( 'section' => 'diff', 'label' => __( 'Point 4 text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'A named contact who already knows the situation, and updates often enough that you’re never left guessing how a visit went.', 'ccs-wp-theme' ) ),
		'costs_eyebrow' => array( 'section' => 'costs', 'label' => __( 'Label above the heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'No hidden fees', 'ccs-wp-theme' ) ),
		'costs_title' => array( 'section' => 'costs', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'What care costs', 'ccs-wp-theme' ) ),
		'costs_lede' => array( 'section' => 'costs', 'label' => __( 'Introduction', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Most providers make you ask. Here are our rates, and every extra that could appear on your invoice — so nothing on it is a surprise.', 'ccs-wp-theme' ) ),
		'costs_funding' => array( 'section' => 'costs', 'label' => __( 'Funding sentence', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Care can be funded privately, through local authority support, or through NHS health funding. We’ll talk you through which applies to you.', 'ccs-wp-theme' ) ),
		'costs_note' => array( 'section' => 'costs', 'label' => __( 'Note under the rate', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Your actual cost depends on the type of care you need and when it happens — this is our lowest rate, not a flat price.', 'ccs-wp-theme' ) ),
		'costs_extras_heading' => array( 'section' => 'costs', 'label' => __( 'Heading above the list of extras', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'What else you might be charged', 'ccs-wp-theme' ) ),
		'reviews_title' => array( 'section' => 'reviews', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'What families say about us', 'ccs-wp-theme' ) ),
		'reviews_lede' => array( 'section' => 'reviews', 'label' => __( 'Introduction', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Published reviews, not selected quotes — each one is dated and you can read it in full at the source.', 'ccs-wp-theme' ) ),
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
		'reviews_button' => array( 'section' => 'reviews', 'label' => __( 'Button label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Read every review on homecare.co.uk', 'ccs-wp-theme' ) ),
		'team_title' => array( 'section' => 'team', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Meet the people behind your care', 'ccs-wp-theme' ) ),
		'team_lede' => array( 'section' => 'team', 'label' => __( 'Introduction', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'The managers and supervisors who run our service. You will know who they are before anyone comes through your door.', 'ccs-wp-theme' ) ),
		'team_button' => array( 'section' => 'team', 'label' => __( 'Button label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Meet the whole team', 'ccs-wp-theme' ) ),
		'partners_title' => array( 'section' => 'partners', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Who we work with', 'ccs-wp-theme' ) ),
		'partners_lede' => array( 'section' => 'partners', 'label' => __( 'Introduction', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'The organisations that regulate us, train our carers and set the standards we work to — alongside the sister services we run ourselves. Several of them you can check us against directly.', 'ccs-wp-theme' ) ),
		'closing_title' => array( 'section' => 'closing', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Not sure where to start?', 'ccs-wp-theme' ) ),
		'closing_text' => array( 'section' => 'closing', 'label' => __( 'Text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Most people who call us are not ready to arrange anything yet — they just want to talk it through with someone who knows how this works. That conversation is free, and there is nothing to sign at the end of it.', 'ccs-wp-theme' ) ),

		// Inner pages.
		'cta_title' => array( 'section' => 'p_cta', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Ready when you are', 'ccs-wp-theme' ) ),
		'cta_text' => array( 'section' => 'p_cta', 'label' => __( 'Text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Start with a conversation, not a commitment. Free, no obligation, no pressure.', 'ccs-wp-theme' ) ),
		'cta_careers_title' => array( 'section' => 'p_cta', 'label' => __( 'Careers pages: heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Fancy joining us?', 'ccs-wp-theme' ) ),
		'cta_careers_text' => array( 'section' => 'p_cta', 'label' => __( 'Careers pages: text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'We are always glad to hear from people who want to do this job properly. Have a look at what is open right now.', 'ccs-wp-theme' ) ),
		'cta_careers_button' => array( 'section' => 'p_cta', 'label' => __( 'Careers pages: button label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'See current vacancies', 'ccs-wp-theme' ) ),
		'contact_title' => array( 'section' => 'p_contact', 'label' => __( 'Page heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Book your free care consultation', 'ccs-wp-theme' ) ),
		'contact_email_note' => array( 'section' => 'p_contact', 'label' => __( 'Note under the email address', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'For anything that does not need an answer today.', 'ccs-wp-theme' ) ),
		'contact_form_eyebrow' => array( 'section' => 'p_contact', 'label' => __( 'Label above the form heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Free, no obligation', 'ccs-wp-theme' ) ),
		'contact_form_title' => array( 'section' => 'p_contact', 'label' => __( 'Form heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Tell us what you need', 'ccs-wp-theme' ) ),
		'contact_talk_title' => array( 'section' => 'p_contact', 'label' => __( 'Call card heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Prefer to talk?', 'ccs-wp-theme' ) ),
		'contact_next_title' => array( 'section' => 'p_contact', 'label' => __( 'Heading above the three steps', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'What happens next', 'ccs-wp-theme' ) ),
		'service_related_title' => array( 'section' => 'p_service', 'label' => __( 'Heading (put *asterisks* round the word to underline)', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Other ways we can *help*', 'ccs-wp-theme' ) ),
		'service_included_title' => array( 'section' => 'p_service', 'label' => __( 'Heading above the list of what is included', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'What’s included', 'ccs-wp-theme' ) ),
		'service_pricing_title' => array( 'section' => 'p_service', 'label' => __( 'Pricing heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Pricing', 'ccs-wp-theme' ) ),
		'service_pricing_note' => array( 'section' => 'p_service', 'label' => __( 'Note under the price', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Your actual cost depends on the type of care you need and when it happens — this is our lowest rate, not a flat price.', 'ccs-wp-theme' ) ),
		'service_faq_title' => array( 'section' => 'p_service', 'label' => __( 'Questions heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Frequently asked questions', 'ccs-wp-theme' ) ),
		'service_callback_eyebrow' => array( 'section' => 'p_service', 'label' => __( 'Label above the call-back heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Free, no obligation', 'ccs-wp-theme' ) ),
		'service_callback_title' => array( 'section' => 'p_service', 'label' => __( 'Call-back form heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Ask us to call you back', 'ccs-wp-theme' ) ),
		'service_hub_label' => array( 'section' => 'p_service', 'label' => __( 'All-services card: small label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'All services', 'ccs-wp-theme' ) ),
		'service_hub_title' => array( 'section' => 'p_service', 'label' => __( 'All-services card: title', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Everything we offer at home', 'ccs-wp-theme' ) ),
		'service_hub_text' => array( 'section' => 'p_service', 'label' => __( 'All-services card: text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'See every kind of care we provide across Maidstone and Kent, and who each one is for.', 'ccs-wp-theme' ) ),
		'service_hub_link' => array( 'section' => 'p_service', 'label' => __( 'All-services card: link text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'See all services', 'ccs-wp-theme' ) ),
		'areas_title' => array( 'section' => 'p_areas', 'label' => __( 'Page heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Areas we cover', 'ccs-wp-theme' ) ),
		'areas_lede' => array( 'section' => 'p_areas', 'label' => __( 'Introduction', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Home care across Maidstone and the Kent towns around it. Choose your area to see the villages we visit and the care we provide there.', 'ccs-wp-theme' ) ),
		'areas_strip_title' => array( 'section' => 'p_areas', 'label' => __( '"Not on the list" heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Not on the list?', 'ccs-wp-theme' ) ),
		'areas_strip_text' => array( 'section' => 'p_areas', 'label' => __( '"Not on the list" text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Ring and ask. The honest answer depends on whether we can staff it consistently.', 'ccs-wp-theme' ) ),
		'careers_expect_title' => array( 'section' => 'p_careers', 'label' => __( 'Heading (put *asterisks* round the word to underline)', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Care work, *done* well', 'ccs-wp-theme' ) ),
		'careers_ways_title' => array( 'section' => 'p_careers', 'label' => __( 'Heading above the three cards', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Find your way in', 'ccs-wp-theme' ) ),
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
		'team_page_eyebrow' => array( 'section' => 'p_team', 'label' => __( 'Label above the team heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Management and office', 'ccs-wp-theme' ) ),
		'team_page_title' => array( 'section' => 'p_team', 'label' => __( 'Team heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Say hello to the team', 'ccs-wp-theme' ) ),
		'team_page_strip_title' => array( 'section' => 'p_team', 'label' => __( 'Careers prompt: heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Want to work with us?', 'ccs-wp-theme' ) ),
		'team_page_strip_text' => array( 'section' => 'p_team', 'label' => __( 'Careers prompt: text', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'We are always glad to hear from kind, reliable people who want to care well.', 'ccs-wp-theme' ) ),
		'team_page_strip_button' => array( 'section' => 'p_team', 'label' => __( 'Careers prompt: button label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'See careers', 'ccs-wp-theme' ) ),
		'faqs_title' => array( 'section' => 'p_faqs', 'label' => __( 'Heading (put *asterisks* round the word to underline)', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Answers, *plainly*', 'ccs-wp-theme' ) ),
		'faqs_box_title' => array( 'section' => 'p_faqs', 'label' => __( 'Help box heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Question not here?', 'ccs-wp-theme' ) ),
		'about_intro_title' => array( 'section' => 'p_about', 'label' => __( 'Heading (put *asterisks* round the word to underline)', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Care that *fits* around your life', 'ccs-wp-theme' ) ),
		'about_story_title' => array( 'section' => 'p_about', 'label' => __( 'Video section heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Where it started', 'ccs-wp-theme' ) ),
		'about_story_text' => array( 'section' => 'p_about', 'label' => __( 'Video section text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'Our company director and registered manager, Victoria Walker, explains how Continuity of Care started here in Kent, and why the service is built on compassion, reliability and quality of life.', 'ccs-wp-theme' ) ),
		'services_page_title' => array( 'section' => 'p_services', 'label' => __( 'Heading (put *asterisks* round the word to underline)', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Care at home, *one team*', 'ccs-wp-theme' ) ),
		'services_page_promise_title' => array( 'section' => 'p_services', 'label' => __( 'Second section heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Whichever service, the same promise', 'ccs-wp-theme' ) ),
		'cqc_page_title' => array( 'section' => 'p_cqc', 'label' => __( 'Intro heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Care you can check for yourself', 'ccs-wp-theme' ) ),
		'e404_eyebrow' => array( 'section' => 'p_404', 'label' => __( 'Small label', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'Page not found', 'ccs-wp-theme' ) ),
		'e404_title' => array( 'section' => 'p_404', 'label' => __( 'Heading', 'ccs-wp-theme' ), 'type' => 'text', 'default' => __( 'That page isn’t here any more', 'ccs-wp-theme' ) ),
		'e404_text' => array( 'section' => 'p_404', 'label' => __( 'Text', 'ccs-wp-theme' ), 'type' => 'textarea', 'default' => __( 'The link may be out of date, or the address may have a typo in it. Nothing is wrong at your end — here’s where most people are heading.', 'ccs-wp-theme' ) ),
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
	$text = ccs_copy( $key );

	if ( preg_match( '/^(.*?)\*([^*]+)\*(.*)$/s', $text, $m ) ) {
		return esc_html( $m[1] ) . ccs_accent( $m[2] ) . esc_html( str_replace( '*', '', $m[3] ) );
	}

	return esc_html( $text );
}
