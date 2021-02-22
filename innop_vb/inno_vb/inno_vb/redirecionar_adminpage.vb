Public Class redirecionar_adminpage
    Private Sub redirecionar_adminpage_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Me.CenterToScreen()
        Timer1.Interval = 4000
        Timer1.Start()
    End Sub

    Private Sub Timer1_Tick(sender As System.Object, e As System.EventArgs) Handles Timer1.Tick
        Timer1.Stop()
        abrir_admin_page()
        End
    End Sub

End Class