const Ziggy = {"url":"https:\/\/test.nartag.com","port":null,"defaults":{},"routes":{"sanctum.csrf-cookie":{"uri":"sanctum\/csrf-cookie","methods":["GET","HEAD"]},"ignition.healthCheck":{"uri":"_ignition\/health-check","methods":["GET","HEAD"]},"ignition.executeSolution":{"uri":"_ignition\/execute-solution","methods":["POST"]},"ignition.updateConfig":{"uri":"_ignition\/update-config","methods":["POST"]},"web.index":{"uri":"\/","methods":["GET","HEAD"]},"account.index":{"uri":"u\/account","methods":["GET","HEAD"]},"account.update":{"uri":"u\/account","methods":["PATCH"]},"account.destroy":{"uri":"u\/account","methods":["DELETE"]},"validateUserAvatar":{"uri":"users\/validate-avatar","methods":["POST"]},"currentUser.update":{"uri":"users\/{id}","methods":["PATCH"]},"shortcut.store":{"uri":"shortcut\/add","methods":["POST"]},"follow_manga.store":{"uri":"u\/follow\/{mangaid}","methods":["POST"]},"unfollow_manga.store":{"uri":"u\/unfollow\/{mangaid}","methods":["POST"]},"view_manga.store":{"uri":"u\/view\/{mangaid}","methods":["POST"]},"unview_manga.store":{"uri":"u\/unview\/{mangaid}","methods":["POST"]},"fav_manga.store":{"uri":"u\/fav\/{mangaid}","methods":["POST"]},"unfav_manga.store":{"uri":"u\/unfav\/{mangaid}","methods":["POST"]},"view_chapter.store":{"uri":"u\/view_chapter\/{mangaid}","methods":["POST"]},"unview_chapter.store":{"uri":"u\/unview_chapter\/{mangaid}","methods":["POST"]},"shortcut.destroy":{"uri":"u\/remove_shortcut","methods":["POST"]},"rate_manga.store":{"uri":"rate\/{manga_id}","methods":["POST"]},"profile.index":{"uri":"u\/{username}\/{page?}","methods":["GET","HEAD"],"wheres":{"page":"siguiendo|favoritos|atajos"}},"library.index":{"uri":"library","methods":["GET","HEAD"]},"manga_detail.index":{"uri":"l\/{slug}","methods":["GET","HEAD"]},"chapter_viewer.index":{"uri":"v\/{manga_slug}\/{chapter_slug}\/{reader_type?}\/{current_page?}","methods":["GET","HEAD"],"wheres":{"reader_type":"paginado","current_page":"[0-9]+"}},"admin.index":{"uri":"controller","methods":["GET","HEAD"]},"manga_demography.index":{"uri":"controller\/manga\/demography","methods":["GET","HEAD"]},"manga_demography.create":{"uri":"controller\/manga\/demography\/create","methods":["GET","HEAD"]},"manga_demography.store":{"uri":"controller\/manga\/demography","methods":["POST"]},"manga_demography.edit":{"uri":"controller\/manga\/demography\/{id}","methods":["GET","HEAD"]},"manga_demography.update":{"uri":"controller\/manga\/demography\/{id}","methods":["PATCH"]},"manga_demography.destroy":{"uri":"controller\/manga\/demography\/{id}","methods":["DELETE"]},"manga_types.index":{"uri":"controller\/manga\/type","methods":["GET","HEAD"]},"manga_types.create":{"uri":"controller\/manga\/type\/create","methods":["GET","HEAD"]},"manga_types.store":{"uri":"controller\/manga\/type","methods":["POST"]},"manga_types.edit":{"uri":"controller\/manga\/type\/{id}","methods":["GET","HEAD"]},"manga_types.update":{"uri":"controller\/manga\/type\/{id}","methods":["PATCH"]},"manga_types.destroy":{"uri":"controller\/manga\/type\/{id}","methods":["DELETE"]},"manga_book_status.index":{"uri":"controller\/manga\/status","methods":["GET","HEAD"]},"manga_book_status.create":{"uri":"controller\/manga\/status\/create","methods":["GET","HEAD"]},"manga_book_status.store":{"uri":"controller\/manga\/status","methods":["POST"]},"manga_book_status.edit":{"uri":"controller\/manga\/status\/{id}","methods":["GET","HEAD"]},"manga_book_status.update":{"uri":"controller\/manga\/status\/{id}","methods":["PATCH"]},"manga_book_status.destroy":{"uri":"controller\/manga\/status\/{id}","methods":["DELETE"]},"manga.index":{"uri":"controller\/manga","methods":["GET","HEAD"]},"manga.create":{"uri":"controller\/manga\/create","methods":["GET","HEAD"]},"manga.store":{"uri":"controller\/manga","methods":["POST"]},"manga.edit":{"uri":"controller\/manga\/{id}","methods":["GET","HEAD"]},"manga.update":{"uri":"controller\/manga\/{id}","methods":["PATCH"]},"manga.destroy":{"uri":"controller\/manga\/{id}","methods":["DELETE"]},"createChapter.store":{"uri":"controller\/chapters\/create-chapter\/{mangaid}","methods":["POST"]},"uploadSingle.store":{"uri":"controller\/chapters\/upload-single-image","methods":["POST"]},"uploadChapter.store":{"uri":"controller\/chapters\/uploadChapter\/{mangaid}","methods":["POST"]},"chapters.show":{"uri":"controller\/chapters\/getChapter\/{mangaid}","methods":["POST"]},"chapters.store":{"uri":"controller\/chapters\/{mangaid}","methods":["POST"]},"subirChapter.imagenes":{"uri":"controller\/chapters\/uploadChapterImages\/{mangaid}","methods":["POST"]},"actualizarOrdenChapter.imagenes":{"uri":"controller\/chapters\/updateChapterOrder\/{chapterid}","methods":["POST"]},"eliminarChapter.imagenes":{"uri":"controller\/chapters\/deleteChapterImage\/{chapterid}","methods":["POST"]},"chapters.update":{"uri":"controller\/chapters\/{chapterid}","methods":["PATCH"]},"chapters.destroy":{"uri":"controller\/chapters\/{chapterid}","methods":["DELETE"]},"categories.index":{"uri":"controller\/categories","methods":["GET","HEAD"]},"categories.create":{"uri":"controller\/categories\/create","methods":["GET","HEAD"]},"categories.store":{"uri":"controller\/categories","methods":["POST"]},"categories.edit":{"uri":"controller\/categories\/{id}","methods":["GET","HEAD"]},"categories.update":{"uri":"controller\/categories\/{id}","methods":["PATCH"]},"categories.destroy":{"uri":"controller\/categories\/{id}","methods":["DELETE"]},"tags.index":{"uri":"controller\/tags","methods":["GET","HEAD"]},"tags.create":{"uri":"controller\/tags\/create","methods":["GET","HEAD"]},"tags.store":{"uri":"controller\/tags","methods":["POST"]},"tags.edit":{"uri":"controller\/tags\/{id}","methods":["GET","HEAD"]},"tags.update":{"uri":"controller\/tags\/{id}","methods":["PATCH"]},"tags.destroy":{"uri":"controller\/tags\/{id}","methods":["DELETE"]},"validateAvatar.store":{"uri":"controller\/users\/validate-avatar","methods":["POST"]},"users.index":{"uri":"controller\/users","methods":["GET","HEAD"]},"users.create":{"uri":"controller\/users\/create","methods":["GET","HEAD"]},"users.store":{"uri":"controller\/users","methods":["POST"]},"users.edit":{"uri":"controller\/users\/{id}","methods":["GET","HEAD"]},"users.update":{"uri":"controller\/users\/{id}","methods":["PATCH"]},"users.destroy":{"uri":"controller\/users\/{id}","methods":["DELETE"]},"permissions.index":{"uri":"controller\/permissions","methods":["GET","HEAD"]},"permissions.create":{"uri":"controller\/permissions\/create","methods":["GET","HEAD"]},"permissions.store":{"uri":"controller\/permissions","methods":["POST"]},"permissions.edit":{"uri":"controller\/permissions\/{id}","methods":["GET","HEAD"]},"permissions.update":{"uri":"controller\/permissions\/{id}","methods":["PATCH"]},"permissions.destroy":{"uri":"controller\/permissions\/{id}","methods":["DELETE"]},"roles.index":{"uri":"controller\/roles","methods":["GET","HEAD"]},"roles.create":{"uri":"controller\/roles\/create","methods":["GET","HEAD"]},"roles.store":{"uri":"controller\/roles","methods":["POST"]},"roles.edit":{"uri":"controller\/roles\/{id}","methods":["GET","HEAD"]},"roles.update":{"uri":"controller\/roles\/{id}","methods":["PATCH"]},"roles.destroy":{"uri":"controller\/roles\/{id}","methods":["DELETE"]},"product_types.index":{"uri":"controller\/products\/type","methods":["GET","HEAD"]},"product_types.create":{"uri":"controller\/products\/type\/create","methods":["GET","HEAD"]},"product_types.store":{"uri":"controller\/products\/type","methods":["POST"]},"product_types.edit":{"uri":"controller\/products\/type\/{id}","methods":["GET","HEAD"]},"product_types.update":{"uri":"controller\/products\/type\/{id}","methods":["PATCH"]},"product_types.destroy":{"uri":"controller\/products\/type\/{id}","methods":["DELETE"]},"products.index":{"uri":"controller\/products","methods":["GET","HEAD"]},"products.create":{"uri":"controller\/products\/create","methods":["GET","HEAD"]},"products.store":{"uri":"controller\/products","methods":["POST"]},"products.edit":{"uri":"controller\/products\/{id}","methods":["GET","HEAD"]},"products.update":{"uri":"controller\/products\/{id}","methods":["PATCH"]},"products.destroy":{"uri":"controller\/products\/{id}","methods":["DELETE"]},"ranks.index":{"uri":"controller\/ranks","methods":["GET","HEAD"]},"ranks.create":{"uri":"controller\/ranks\/create","methods":["GET","HEAD"]},"ranks.store":{"uri":"controller\/ranks","methods":["POST"]},"ranks.edit":{"uri":"controller\/ranks\/{id}","methods":["GET","HEAD"]},"ranks.update":{"uri":"controller\/ranks\/{id}","methods":["PATCH"]},"ranks.destroy":{"uri":"controller\/ranks\/{id}","methods":["DELETE"]},"slider.index":{"uri":"controller\/slider","methods":["GET","HEAD"]},"slider.create":{"uri":"controller\/slider\/create","methods":["GET","HEAD"]},"slider.store":{"uri":"controller\/slider","methods":["POST"]},"slider.edit":{"uri":"controller\/slider\/{id}","methods":["GET","HEAD"]},"slider.update":{"uri":"controller\/slider\/{id}","methods":["PATCH"]},"slider.destroy":{"uri":"controller\/slider\/{id}","methods":["DELETE"]},"settings.index":{"uri":"controller\/settings","methods":["GET","HEAD"]},"settings.update":{"uri":"controller\/settings","methods":["PATCH"]},"settings.ads.index":{"uri":"controller\/settings\/ads","methods":["GET","HEAD"]},"settings.ads.store":{"uri":"controller\/settings\/ads","methods":["POST"]},"settings.ads.update":{"uri":"controller\/settings\/ads","methods":["PATCH"]},"settings.seo.index":{"uri":"controller\/settings\/seo","methods":["GET","HEAD"]},"settings.seo.store":{"uri":"controller\/settings\/seo","methods":["POST"]},"settings.seo.update":{"uri":"controller\/settings\/seo","methods":["PATCH"]},"register":{"uri":"register","methods":["GET","HEAD"]},"login":{"uri":"login","methods":["GET","HEAD"]},"password.request":{"uri":"forgot-password","methods":["GET","HEAD"]},"password.email":{"uri":"forgot-password","methods":["POST"]},"password.reset":{"uri":"reset-password\/{token}","methods":["GET","HEAD"]},"password.store":{"uri":"reset-password","methods":["POST"]},"verification.notice":{"uri":"verify-email","methods":["GET","HEAD"]},"verification.verify":{"uri":"verify-email\/{id}\/{hash}","methods":["GET","HEAD"]},"verification.send":{"uri":"email\/verification-notification","methods":["POST"]},"password.confirm":{"uri":"confirm-password","methods":["GET","HEAD"]},"password.update":{"uri":"password","methods":["PUT"]},"logout":{"uri":"logout","methods":["POST"]}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy };
