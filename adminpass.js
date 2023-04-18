const { __ } = wp.i18n;
const { registerPlugin } = wp.plugins;
const { PluginDocumentSettingPanel } = wp.editPost;
const { withSelect } = wp.data;
const { Fragment } = wp.element;

const PasswordForAdminPanel = withSelect((select) => {
    const { getEditedPostAttribute } = select("core/editor");
    return {
        password: getEditedPostAttribute("password"),
    };
})(({ password }) => {
    return wp.element.createElement(
        Fragment,
        {},
        wp.element.createElement(
            PluginDocumentSettingPanel,
            {
                name: "admin-password-panel",
                title: __("Password for Protection"),
                className: "admin-password-panel",
            },
            password
                ? wp.element.createElement(
                      "p",
                      {},
                      __("Password: "),
                      wp.element.createElement("strong", {}, password)
                  )
                : wp.element.createElement(
                      "p",
                      {},
                      __("This post is not password-protected.")
                  )
        )
    );
});

registerPlugin("adminpass", {
    render: PasswordForAdminPanel,
});
