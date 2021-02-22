﻿Imports MySql.Data.MySqlClient
Imports System.IO

Public Class index
    Private Sub PictureBox2_Click(sender As Object, e As EventArgs) Handles PictureBox2.Click
        formlogin.Show()
        Me.Close()
    End Sub

    Dim con As MySqlConnection = connect_db()

    Private Sub index_Load(sender As Object, e As EventArgs) Handles MyBase.Load

        Me.CenterToScreen()

        Me.Text = "INNOP - Página Inicial"

        Button1.BackColor = Color.PaleTurquoise

        panelPagInicial.Visible = True
        panelPagInicial.Location = New Point(-5, 90)
        panelPagInicial.Size = New Point(1134, 555)

        panelSumarios.Visible = False

        'pag inicial
        If Not get_dados_info(g.np, g.cc) Then
            MsgBox("index_load.get_dados_info() -> Falha ao tentar obter dados.", vbCritical, "INNOP - ERRO")
        ElseIf Not fill_datagrid_horario() Then
            MsgBox("index_load.fill_datagrid_horario() -> Falha ao tentar obter horario.", vbCritical, "INNOP - ERRO")
        End If

        'sumarios
        cbDisciplina.Enabled = False
        cbLicao.Enabled = False

        If Not fill_combobox_turma(g.np) Then
            MsgBox("index_load.fill_combobox_turma() -> Falha ao tentar obter turmas.", vbCritical, "INNOP - ERRO")
        End If


    End Sub

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        Me.Text = "INNOP - Página Inicial"

        Button1.BackColor = Color.PaleTurquoise
        Button2.BackColor = Color.White
        Button3.BackColor = Color.White

        panelPagInicial.Visible = True
        panelSumarios.Visible = False

        panelPagInicial.Location = New Point(-5, 90)
        panelPagInicial.Size = New Point(1134, 555)

    End Sub

    Private Sub Button2_Click(sender As Object, e As EventArgs) Handles Button2.Click
        Me.Text = "INNOP - Sumários"

        Button1.BackColor = Color.White
        Button2.BackColor = Color.PaleTurquoise
        Button3.BackColor = Color.White

        panelPagInicial.Visible = False
        panelSumarios.Visible = True


    End Sub

    Private Sub Button3_Click(sender As Object, e As EventArgs) Handles Button3.Click
        Me.Text = "INNOP - Faltas"

        Button1.BackColor = Color.White
        Button2.BackColor = Color.White
        Button3.BackColor = Color.PaleTurquoise

        panelPagInicial.Visible = False
        panelSumarios.Visible = False
    End Sub

    Private Sub cbTurma_SelectedIndexChanged(sender As Object, e As EventArgs) Handles cbTurma.SelectedIndexChanged
        If cbTurma.SelectedIndex >= 0 Then
            cbDisciplina.ResetText()
            cbDisciplina.Items.Clear()
            cbLicao.ResetText()
            cbLicao.Items.Clear()

            cbDisciplina.Enabled = True
            cbLicao.Enabled = False

            Dim ano As String = cbTurma.SelectedItem
            fill_combobox_disciplina(ano(0) & ano(1))
        End If
    End Sub

    Private Sub cbDisciplina_SelectedIndexChanged(sender As Object, e As EventArgs) Handles cbDisciplina.SelectedIndexChanged
        If cbDisciplina.SelectedIndex >= 0 Then
            cbLicao.ResetText()
            cbLicao.Items.Clear()

            cbDisciplina.Enabled = True
            cbLicao.Enabled = True

            fill_combobox_licoes()
        End If
    End Sub

    Private Sub cbLicao_SelectedIndexChanged(sender As Object, e As EventArgs) Handles cbLicao.SelectedIndexChanged
        If cbDisciplina.SelectedIndex >= 0 Then

            textboxSumario.Text = ""

            gbSumario.Text = cbTurma.SelectedItem & " - " & cbDisciplina.SelectedItem & "- Lição " & cbLicao.SelectedItem
            textboxSumario.Enabled = False

            ' se a licao selecionada no drop menu é a ultima +1 (ou seja , ele quer introduzir uma nova)
            If cbLicao.SelectedIndex + 1 = g.max_lic + 1 Then

                ' groupbox title
                gbSumario.Text = cbTurma.SelectedItem & " - " & cbDisciplina.SelectedItem & "- Introduzir Lição " & cbLicao.SelectedItem

                ' deixa-lo introduzir sumario
                textboxSumario.Enabled = True

                ' buttons enabled!
                Button7.Enabled = True
                Button8.Enabled = True
                Button7.Visible = True
                Button8.Visible = True
            Else
                Button7.Enabled = False
                Button8.Enabled = False
                Button7.Visible = False
                Button8.Visible = False
                If Not (get_sumario()) Then
                    MsgBox("Sumário não existe!", vbInformation, "INNOP")
                End If

            End If

        End If
    End Sub

    Private Sub Button8_Click(sender As Object, e As EventArgs) Handles Button8.Click
        textboxSumario.Text = ""

    End Sub

    Private Sub Button7_Click(sender As Object, e As EventArgs) Handles Button7.Click
        If textboxSumario.Text = "" Or textboxSumario.Text = " " Then
            MsgBox("Sumário nulo!", vbCritical, "INNOP - ERRO")
        Else
            If Not (insert_sumario()) Then
                MsgBox("Sumário não introduzido!", vbCritical, "INNOP - ERRO")
            Else
                MsgBox("Sumário introduzido!", vbInformation, "INNOP")
                textboxSumario.Text = ""
            End If
        End If
    End Sub
End Class