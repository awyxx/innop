Imports MySql.Data.MySqlClient
Imports System.IO
Imports System.ComponentModel
Imports System.Data.SqlClient

Public Class formlogin
    ' np
    Private Sub TextBox1_TextChanged(sender As Object, e As EventArgs) Handles TextBox1.TextChanged
        If Label1.Text = "Nº de Processo" Then
            Label1.Text = ""
            Return
        End If
        If TextBox1.Text = "" Then
            Label1.Text = "Nº de Processo"
        End If
    End Sub

    ' cc
    Private Sub TextBox2_TextChanged(sender As Object, e As EventArgs) Handles TextBox2.TextChanged
        If Label2.Text = "CC" Then
            Label2.Text = ""
            Return
        End If
        If TextBox2.Text = "" Then
            Label2.Text = "CC"
        End If
    End Sub

    ' x - fechar
    Private Sub Button2_Click(sender As Object, e As EventArgs) Handles Button2.Click
        End
    End Sub

    ' iniciar sessão
    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        g.np = TextBox1.Text
        g.cc = TextBox2.Text
        If (check_login(TextBox1.Text, TextBox2.Text)) Then
            'nada? xd
        Else
            MsgBox("Login incorreto! Verifica os teus dados e tenta de novo.", vbCritical, "INNOP - Dados incorretos")
        End If

    End Sub

    Private Sub formlogin_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Me.CenterToScreen()
        reset_vars()
    End Sub
End Class
