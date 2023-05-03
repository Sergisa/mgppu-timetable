function showOverlay() {
    $(document.body).append(
        $("<div class='mask-overlay row'></div>").css({
            zIndex: 1000,
            width: '100vw',
            height: '100vh',
            position: "fixed",
            top: 0,
            left: 0,
            display: "flex",
        })
    );
    for (let i = 0; i < 12; i++) {
        var column = $("<span class='column col-1'></span>")
        console.log(column.get(0))
        $('.mask-overlay').append(
            column.css({
                margin: column.css('padding'),
                backgroundColor: 'rgba(204,204,204,0.56)',
                borderRight: '1px solid red',
                height: "100vh"
            })
        )
    }
}