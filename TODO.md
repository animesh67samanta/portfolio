# Refactor Laravel Portfolio to Best Practices - TODO

## Approved Plan Steps (Breakdown):

### Phase 1: Backend Structure & Services
- [x] 1. Create `app/Services/ImageService.php` (shared image upload/delete).
- [x] 2. Add scopes to models (e.g. Project: featured(), published()).
- [x] 3. Create `app/Services/Frontend/PortfolioService.php` (aggregate home data).
- [x] 4. Create Admin Services: `app/Services/Admin/BannerService.php`, `ProjectService.php` etc. for all 7 resources (About, Banner, Blog, Contact, Project, Skill, Testimonial). (Partial: Banner, Project done)
 - [x] 5. Refactor controllers: inject/use services (start with HomeController, then Admin/ProjectController, BannerController, then rest). (Partial: Home, Project, Banner)
 - [x] 6. `execute_command: composer dump-autoload`

### Phase 2: Vue Components & Pages
 - [x] 7. Create reusable Admin comps: `resources/js/Components/Admin/DataTable.vue`, `ImageUploader.vue`, `ResourceForm.vue`. (Partial: DataTable, ImageUploader)
 - [x] 8. Refactor Admin pages: e.g. `Admin/Projects/Index.vue`, `Banners/Index.vue` to use new comps. (Partial: Projects/Index)

- [ ] 9. Create Frontend comps: `Components/Frontend/Hero.vue`, `SkillsGrid.vue`, `ProjectsGrid.vue`.
- [ ] 10. Implement `Front/Home.vue`: display portfolio data using comps.

### Phase 3: Optimizations & Tests
- [ ] 11. Add caching to services (e.g. Cache::remember).
- [ ] 12. Update tests if needed.
- [ ] 13. `npm run build`, test app.

## Progress
Ready for Phase 1 Step 1.

**Next action: Approve or adjust TODO?**
