<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */
//MODULO: licitacao
//CLASSE DA ENTIDADE liclicita
class cl_liclicita {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $l20_codigo = 0;
   var $l20_codtipocom = 0;
   var $l20_numero = 0;
   var $l20_id_usucria = 0;
   var $l20_datacria_dia = null;
   var $l20_datacria_mes = null;
   var $l20_datacria_ano = null;
   var $l20_datacria = null;
   var $l20_horacria = null;
   var $l20_dataaber_dia = null;
   var $l20_dataaber_mes = null;
   var $l20_dataaber_ano = null;
   var $l20_dataaber = null;
   var $l20_dtpublic_dia = null;
   var $l20_dtpublic_mes = null;
   var $l20_dtpublic_ano = null;
   var $l20_dtpublic = null;
   var $l20_horaaber = null;
   var $l20_local = null;
   var $l20_objeto = null;
   var $l20_tipojulg = 0;
   var $l20_liccomissao = 0;
   var $l20_liclocal = 0;
   var $l20_procadmin = null;
   var $l20_correto = 'f';
   var $l20_instit = 0;
   var $l20_licsituacao = 0;
   var $l20_edital = 0;
   var $l20_anousu = 0;
   var $l20_usaregistropreco = 'f';
   var $l20_localentrega = null;
   var $l20_prazoentrega = null;
   var $l20_condicoespag = null;
   var $l20_validadeproposta = null;
   var $l20_formacontroleregistropreco = 0;
   var $l20_tipo = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 l20_codigo = int8 = Cod. Sequencial
                 l20_codtipocom = int4 = Código do tipo de compra
                 l20_numero = int8 = Numeração
                 l20_id_usucria = int4 = Cod. Usuário
                 l20_datacria = date = Data Criação
                 l20_horacria = char(5) = Hora Criação
                 l20_dataaber = date = Data Abertura
                 l20_dtpublic = date = Data Publicação
                 l20_horaaber = char(5) = Hora Abertura
                 l20_local = text = Local da Licitação
                 l20_objeto = text = Objeto
                 l20_tipojulg = int4 = Tipo de Julgamento
                 l20_liccomissao = int4 = Código da Comissão
                 l20_liclocal = int4 = Código do Local da Licitação
                 l20_procadmin = varchar(50) = Processo Administrativo
                 l20_correto = bool = Correto
                 l20_instit = int4 = Instituição
                 l20_licsituacao = int4 = Situação da Licitação
                 l20_edital = int8 = Edital
                 l20_anousu = int4 = Exercício
                 l20_usaregistropreco = bool = Registro Preço
                 l20_localentrega = text = Local de Entrega
                 l20_prazoentrega = text = Prazo Entrega
                 l20_condicoespag = text = Condições de Pagamento
                 l20_validadeproposta = text = Validade da Proposta
                 l20_formacontroleregistropreco = int4 = Forma de Controle RP
                 l20_tipo = int4 = Tipo
                 ";
   //funcao construtor da classe
   function cl_liclicita() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liclicita");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->l20_codigo = ($this->l20_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_codigo"]:$this->l20_codigo);
       $this->l20_codtipocom = ($this->l20_codtipocom == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_codtipocom"]:$this->l20_codtipocom);
       $this->l20_numero = ($this->l20_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_numero"]:$this->l20_numero);
       $this->l20_id_usucria = ($this->l20_id_usucria == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_id_usucria"]:$this->l20_id_usucria);
       if($this->l20_datacria == ""){
         $this->l20_datacria_dia = ($this->l20_datacria_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_datacria_dia"]:$this->l20_datacria_dia);
         $this->l20_datacria_mes = ($this->l20_datacria_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_datacria_mes"]:$this->l20_datacria_mes);
         $this->l20_datacria_ano = ($this->l20_datacria_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_datacria_ano"]:$this->l20_datacria_ano);
         if($this->l20_datacria_dia != ""){
            $this->l20_datacria = $this->l20_datacria_ano."-".$this->l20_datacria_mes."-".$this->l20_datacria_dia;
         }
       }
       $this->l20_horacria = ($this->l20_horacria == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_horacria"]:$this->l20_horacria);
       if($this->l20_dataaber == ""){
         $this->l20_dataaber_dia = ($this->l20_dataaber_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_dataaber_dia"]:$this->l20_dataaber_dia);
         $this->l20_dataaber_mes = ($this->l20_dataaber_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_dataaber_mes"]:$this->l20_dataaber_mes);
         $this->l20_dataaber_ano = ($this->l20_dataaber_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_dataaber_ano"]:$this->l20_dataaber_ano);
         if($this->l20_dataaber_dia != ""){
            $this->l20_dataaber = $this->l20_dataaber_ano."-".$this->l20_dataaber_mes."-".$this->l20_dataaber_dia;
         }
       }
       if($this->l20_dtpublic == ""){
         $this->l20_dtpublic_dia = ($this->l20_dtpublic_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_dtpublic_dia"]:$this->l20_dtpublic_dia);
         $this->l20_dtpublic_mes = ($this->l20_dtpublic_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_dtpublic_mes"]:$this->l20_dtpublic_mes);
         $this->l20_dtpublic_ano = ($this->l20_dtpublic_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_dtpublic_ano"]:$this->l20_dtpublic_ano);
         if($this->l20_dtpublic_dia != ""){
            $this->l20_dtpublic = $this->l20_dtpublic_ano."-".$this->l20_dtpublic_mes."-".$this->l20_dtpublic_dia;
         }
       }
       $this->l20_horaaber = ($this->l20_horaaber == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_horaaber"]:$this->l20_horaaber);
       $this->l20_local = ($this->l20_local == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_local"]:$this->l20_local);
       $this->l20_objeto = ($this->l20_objeto == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_objeto"]:$this->l20_objeto);
       $this->l20_tipojulg = ($this->l20_tipojulg == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_tipojulg"]:$this->l20_tipojulg);
       $this->l20_liccomissao = ($this->l20_liccomissao == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_liccomissao"]:$this->l20_liccomissao);
       $this->l20_liclocal = ($this->l20_liclocal == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_liclocal"]:$this->l20_liclocal);
       $this->l20_procadmin = ($this->l20_procadmin == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_procadmin"]:$this->l20_procadmin);
       $this->l20_correto = ($this->l20_correto == "f"?@$GLOBALS["HTTP_POST_VARS"]["l20_correto"]:$this->l20_correto);
       $this->l20_instit = ($this->l20_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_instit"]:$this->l20_instit);
       $this->l20_licsituacao = ($this->l20_licsituacao == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_licsituacao"]:$this->l20_licsituacao);
       $this->l20_edital = ($this->l20_edital == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_edital"]:$this->l20_edital);
       $this->l20_anousu = ($this->l20_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_anousu"]:$this->l20_anousu);
       $this->l20_usaregistropreco = ($this->l20_usaregistropreco == "f"?@$GLOBALS["HTTP_POST_VARS"]["l20_usaregistropreco"]:$this->l20_usaregistropreco);
       $this->l20_localentrega = ($this->l20_localentrega == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_localentrega"]:$this->l20_localentrega);
       $this->l20_prazoentrega = ($this->l20_prazoentrega == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_prazoentrega"]:$this->l20_prazoentrega);
       $this->l20_condicoespag = ($this->l20_condicoespag == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_condicoespag"]:$this->l20_condicoespag);
       $this->l20_validadeproposta = ($this->l20_validadeproposta == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_validadeproposta"]:$this->l20_validadeproposta);
       $this->l20_formacontroleregistropreco = ($this->l20_formacontroleregistropreco == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_formacontroleregistropreco"]:$this->l20_formacontroleregistropreco);
       $this->l20_tipo = ($this->l20_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_tipo"]:$this->l20_tipo);
     }else{
       $this->l20_codigo = ($this->l20_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l20_codigo"]:$this->l20_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($l20_codigo){
      $this->atualizacampos();
     if($this->l20_codtipocom == null ){
       $this->erro_sql = " Campo Código do tipo de compra não informado.";
       $this->erro_campo = "l20_codtipocom";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_numero == null ){
       $this->erro_sql = " Campo Numeração não informado.";
       $this->erro_campo = "l20_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_id_usucria == null ){
       $this->erro_sql = " Campo Cod. Usuário não informado.";
       $this->erro_campo = "l20_id_usucria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_datacria == null ){
       $this->erro_sql = " Campo Data Criação não informado.";
       $this->erro_campo = "l20_datacria_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_horacria == null ){
       $this->erro_sql = " Campo Hora Criação não informado.";
       $this->erro_campo = "l20_horacria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_dataaber == null ){
       $this->erro_sql = " Campo Data Abertura não informado.";
       $this->erro_campo = "l20_dataaber_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_dtpublic == null ){
       $this->l20_dtpublic = "null";
     }
     if($this->l20_horaaber == null ){
       $this->erro_sql = " Campo Hora Abertura não informado.";
       $this->erro_campo = "l20_horaaber";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_local == null ){
       $this->erro_sql = " Campo Local da Licitação não informado.";
       $this->erro_campo = "l20_local";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_objeto == null ){
       $this->erro_sql = " Campo Objeto não informado.";
       $this->erro_campo = "l20_objeto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_tipojulg == null ){
       $this->erro_sql = " Campo Tipo de Julgamento não informado.";
       $this->erro_campo = "l20_tipojulg";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_liccomissao == null ){
       $this->erro_sql = " Campo Código da Comissão não informado.";
       $this->erro_campo = "l20_liccomissao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_liclocal == null ){
       $this->erro_sql = " Campo Código do Local da Licitação não informado.";
       $this->erro_campo = "l20_liclocal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_procadmin == null ){
       $this->l20_procadmin = "0";
     }
     if($this->l20_correto == null ){
       $this->erro_sql = " Campo Correto não informado.";
       $this->erro_campo = "l20_correto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_instit == null ){
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "l20_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_licsituacao == null ){
       $this->erro_sql = " Campo Situação da Licitação não informado.";
       $this->erro_campo = "l20_licsituacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_edital == null ){
       $this->erro_sql = " Campo Edital não informado.";
       $this->erro_campo = "l20_edital";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_anousu == null ){
       $this->erro_sql = " Campo Exercício não informado.";
       $this->erro_campo = "l20_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_usaregistropreco == null ){
       $this->erro_sql = " Campo Registro Preço não informado.";
       $this->erro_campo = "l20_usaregistropreco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l20_formacontroleregistropreco == null ){
       $this->l20_formacontroleregistropreco = "1";
     }
     if($this->l20_tipo == null ){
       $this->erro_sql = " Campo Tipo não informado.";
       $this->erro_campo = "l20_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l20_codigo == "" || $l20_codigo == null ){
       $result = db_query("select nextval('liclicita_l20_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liclicita_l20_codigo_seq do campo: l20_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->l20_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from liclicita_l20_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $l20_codigo)){
         $this->erro_sql = " Campo l20_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l20_codigo = $l20_codigo;
       }
     }
     if(($this->l20_codigo == null) || ($this->l20_codigo == "") ){
       $this->erro_sql = " Campo l20_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liclicita(
                                       l20_codigo
                                      ,l20_codtipocom
                                      ,l20_numero
                                      ,l20_id_usucria
                                      ,l20_datacria
                                      ,l20_horacria
                                      ,l20_dataaber
                                      ,l20_dtpublic
                                      ,l20_horaaber
                                      ,l20_local
                                      ,l20_objeto
                                      ,l20_tipojulg
                                      ,l20_liccomissao
                                      ,l20_liclocal
                                      ,l20_procadmin
                                      ,l20_correto
                                      ,l20_instit
                                      ,l20_licsituacao
                                      ,l20_edital
                                      ,l20_anousu
                                      ,l20_usaregistropreco
                                      ,l20_localentrega
                                      ,l20_prazoentrega
                                      ,l20_condicoespag
                                      ,l20_validadeproposta
                                      ,l20_formacontroleregistropreco
                                      ,l20_tipo
                       )
                values (
                                $this->l20_codigo
                               ,$this->l20_codtipocom
                               ,$this->l20_numero
                               ,$this->l20_id_usucria
                               ,".($this->l20_datacria == "null" || $this->l20_datacria == ""?"null":"'".$this->l20_datacria."'")."
                               ,'$this->l20_horacria'
                               ,".($this->l20_dataaber == "null" || $this->l20_dataaber == ""?"null":"'".$this->l20_dataaber."'")."
                               ,".($this->l20_dtpublic == "null" || $this->l20_dtpublic == ""?"null":"'".$this->l20_dtpublic."'")."
                               ,'$this->l20_horaaber'
                               ,'$this->l20_local'
                               ,'$this->l20_objeto'
                               ,$this->l20_tipojulg
                               ,$this->l20_liccomissao
                               ,$this->l20_liclocal
                               ,'$this->l20_procadmin'
                               ,'$this->l20_correto'
                               ,$this->l20_instit
                               ,$this->l20_licsituacao
                               ,$this->l20_edital
                               ,$this->l20_anousu
                               ,'$this->l20_usaregistropreco'
                               ,'$this->l20_localentrega'
                               ,'$this->l20_prazoentrega'
                               ,'$this->l20_condicoespag'
                               ,'$this->l20_validadeproposta'
                               ,$this->l20_formacontroleregistropreco
                               ,$this->l20_tipo
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "liclicita ($this->l20_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "liclicita já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "liclicita ($this->l20_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->l20_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->l20_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7589,'$this->l20_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1260,7589,'','".AddSlashes(pg_result($resaco,0,'l20_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,7590,'','".AddSlashes(pg_result($resaco,0,'l20_codtipocom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,7594,'','".AddSlashes(pg_result($resaco,0,'l20_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,7592,'','".AddSlashes(pg_result($resaco,0,'l20_id_usucria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,7591,'','".AddSlashes(pg_result($resaco,0,'l20_datacria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,7593,'','".AddSlashes(pg_result($resaco,0,'l20_horacria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,7595,'','".AddSlashes(pg_result($resaco,0,'l20_dataaber'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,7596,'','".AddSlashes(pg_result($resaco,0,'l20_dtpublic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,7597,'','".AddSlashes(pg_result($resaco,0,'l20_horaaber'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,7598,'','".AddSlashes(pg_result($resaco,0,'l20_local'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,7599,'','".AddSlashes(pg_result($resaco,0,'l20_objeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,7782,'','".AddSlashes(pg_result($resaco,0,'l20_tipojulg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,7909,'','".AddSlashes(pg_result($resaco,0,'l20_liccomissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,7908,'','".AddSlashes(pg_result($resaco,0,'l20_liclocal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,8986,'','".AddSlashes(pg_result($resaco,0,'l20_procadmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,10010,'','".AddSlashes(pg_result($resaco,0,'l20_correto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,10103,'','".AddSlashes(pg_result($resaco,0,'l20_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,10287,'','".AddSlashes(pg_result($resaco,0,'l20_licsituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,12605,'','".AddSlashes(pg_result($resaco,0,'l20_edital'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,12606,'','".AddSlashes(pg_result($resaco,0,'l20_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,15270,'','".AddSlashes(pg_result($resaco,0,'l20_usaregistropreco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,15424,'','".AddSlashes(pg_result($resaco,0,'l20_localentrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,15425,'','".AddSlashes(pg_result($resaco,0,'l20_prazoentrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,15426,'','".AddSlashes(pg_result($resaco,0,'l20_condicoespag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,15427,'','".AddSlashes(pg_result($resaco,0,'l20_validadeproposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,20854,'','".AddSlashes(pg_result($resaco,0,'l20_formacontroleregistropreco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1260,22362,'','".AddSlashes(pg_result($resaco,0,'l20_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($l20_codigo=null) {
      $this->atualizacampos();
     $sql = " update liclicita set ";
     $virgula = "";
     if(trim($this->l20_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_codigo"])){
       $sql  .= $virgula." l20_codigo = $this->l20_codigo ";
       $virgula = ",";
       if(trim($this->l20_codigo) == null ){
         $this->erro_sql = " Campo Cod. Sequencial não informado.";
         $this->erro_campo = "l20_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l20_codtipocom)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_codtipocom"])){
       $sql  .= $virgula." l20_codtipocom = $this->l20_codtipocom ";
       $virgula = ",";
       if(trim($this->l20_codtipocom) == null ){
         $this->erro_sql = " Campo Código do tipo de compra não informado.";
         $this->erro_campo = "l20_codtipocom";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l20_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_numero"])){
       $sql  .= $virgula." l20_numero = $this->l20_numero ";
       $virgula = ",";
       if(trim($this->l20_numero) == null ){
         $this->erro_sql = " Campo Numeração não informado.";
         $this->erro_campo = "l20_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l20_id_usucria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_id_usucria"])){
       $sql  .= $virgula." l20_id_usucria = $this->l20_id_usucria ";
       $virgula = ",";
       if(trim($this->l20_id_usucria) == null ){
         $this->erro_sql = " Campo Cod. Usuário não informado.";
         $this->erro_campo = "l20_id_usucria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l20_datacria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_datacria_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l20_datacria_dia"] !="") ){
       $sql  .= $virgula." l20_datacria = '$this->l20_datacria' ";
       $virgula = ",";
       if(trim($this->l20_datacria) == null ){
         $this->erro_sql = " Campo Data Criação não informado.";
         $this->erro_campo = "l20_datacria_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["l20_datacria_dia"])){
         $sql  .= $virgula." l20_datacria = null ";
         $virgula = ",";
         if(trim($this->l20_datacria) == null ){
           $this->erro_sql = " Campo Data Criação não informado.";
           $this->erro_campo = "l20_datacria_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->l20_horacria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_horacria"])){
       $sql  .= $virgula." l20_horacria = '$this->l20_horacria' ";
       $virgula = ",";
       if(trim($this->l20_horacria) == null ){
         $this->erro_sql = " Campo Hora Criação não informado.";
         $this->erro_campo = "l20_horacria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l20_dataaber)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_dataaber_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l20_dataaber_dia"] !="") ){
       $sql  .= $virgula." l20_dataaber = '$this->l20_dataaber' ";
       $virgula = ",";
       if(trim($this->l20_dataaber) == null ){
         $this->erro_sql = " Campo Data Abertura não informado.";
         $this->erro_campo = "l20_dataaber_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["l20_dataaber_dia"])){
         $sql  .= $virgula." l20_dataaber = null ";
         $virgula = ",";
         if(trim($this->l20_dataaber) == null ){
           $this->erro_sql = " Campo Data Abertura não informado.";
           $this->erro_campo = "l20_dataaber_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->l20_dtpublic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_dtpublic_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l20_dtpublic_dia"] !="") ){
       $sql  .= $virgula." l20_dtpublic = '$this->l20_dtpublic' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["l20_dtpublic_dia"])){
         $sql  .= $virgula." l20_dtpublic = null ";
         $virgula = ",";
       }
     }
     if(trim($this->l20_horaaber)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_horaaber"])){
       $sql  .= $virgula." l20_horaaber = '$this->l20_horaaber' ";
       $virgula = ",";
       if(trim($this->l20_horaaber) == null ){
         $this->erro_sql = " Campo Hora Abertura não informado.";
         $this->erro_campo = "l20_horaaber";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l20_local)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_local"])){
       $sql  .= $virgula." l20_local = '$this->l20_local' ";
       $virgula = ",";
       if(trim($this->l20_local) == null ){
         $this->erro_sql = " Campo Local da Licitação não informado.";
         $this->erro_campo = "l20_local";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l20_objeto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_objeto"])){
       $sql  .= $virgula." l20_objeto = '$this->l20_objeto' ";
       $virgula = ",";
       if(trim($this->l20_objeto) == null ){
         $this->erro_sql = " Campo Objeto não informado.";
         $this->erro_campo = "l20_objeto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l20_tipojulg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_tipojulg"])){
       $sql  .= $virgula." l20_tipojulg = $this->l20_tipojulg ";
       $virgula = ",";
       if(trim($this->l20_tipojulg) == null ){
         $this->erro_sql = " Campo Tipo de Julgamento não informado.";
         $this->erro_campo = "l20_tipojulg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l20_liccomissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_liccomissao"])){
       $sql  .= $virgula." l20_liccomissao = $this->l20_liccomissao ";
       $virgula = ",";
       if(trim($this->l20_liccomissao) == null ){
         $this->erro_sql = " Campo Código da Comissão não informado.";
         $this->erro_campo = "l20_liccomissao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l20_liclocal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_liclocal"])){
       $sql  .= $virgula." l20_liclocal = $this->l20_liclocal ";
       $virgula = ",";
       if(trim($this->l20_liclocal) == null ){
         $this->erro_sql = " Campo Código do Local da Licitação não informado.";
         $this->erro_campo = "l20_liclocal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l20_procadmin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_procadmin"])){
       $sql  .= $virgula." l20_procadmin = '$this->l20_procadmin' ";
       $virgula = ",";
     }
     if(trim($this->l20_correto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_correto"])){
       $sql  .= $virgula." l20_correto = '$this->l20_correto' ";
       $virgula = ",";
       if(trim($this->l20_correto) == null ){
         $this->erro_sql = " Campo Correto não informado.";
         $this->erro_campo = "l20_correto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l20_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_instit"])){
       $sql  .= $virgula." l20_instit = $this->l20_instit ";
       $virgula = ",";
       if(trim($this->l20_instit) == null ){
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "l20_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l20_licsituacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_licsituacao"])){
       $sql  .= $virgula." l20_licsituacao = $this->l20_licsituacao ";
       $virgula = ",";
       if(trim($this->l20_licsituacao) == null ){
         $this->erro_sql = " Campo Situação da Licitação não informado.";
         $this->erro_campo = "l20_licsituacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l20_edital)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_edital"])){
       $sql  .= $virgula." l20_edital = $this->l20_edital ";
       $virgula = ",";
       if(trim($this->l20_edital) == null ){
         $this->erro_sql = " Campo Edital não informado.";
         $this->erro_campo = "l20_edital";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l20_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_anousu"])){
       $sql  .= $virgula." l20_anousu = $this->l20_anousu ";
       $virgula = ",";
       if(trim($this->l20_anousu) == null ){
         $this->erro_sql = " Campo Exercício não informado.";
         $this->erro_campo = "l20_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l20_usaregistropreco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_usaregistropreco"])){
       $sql  .= $virgula." l20_usaregistropreco = '$this->l20_usaregistropreco' ";
       $virgula = ",";
       if(trim($this->l20_usaregistropreco) == null ){
         $this->erro_sql = " Campo Registro Preço não informado.";
         $this->erro_campo = "l20_usaregistropreco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l20_localentrega)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_localentrega"])){
       $sql  .= $virgula." l20_localentrega = '$this->l20_localentrega' ";
       $virgula = ",";
     }
     if(trim($this->l20_prazoentrega)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_prazoentrega"])){
       $sql  .= $virgula." l20_prazoentrega = '$this->l20_prazoentrega' ";
       $virgula = ",";
     }
     if(trim($this->l20_condicoespag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_condicoespag"])){
       $sql  .= $virgula." l20_condicoespag = '$this->l20_condicoespag' ";
       $virgula = ",";
     }
     if(trim($this->l20_validadeproposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_validadeproposta"])){
       $sql  .= $virgula." l20_validadeproposta = '$this->l20_validadeproposta' ";
       $virgula = ",";
     }
     if(trim($this->l20_formacontroleregistropreco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_formacontroleregistropreco"])){
        if(trim($this->l20_formacontroleregistropreco)=="" && isset($GLOBALS["HTTP_POST_VARS"]["l20_formacontroleregistropreco"])){
           $this->l20_formacontroleregistropreco = "0" ;
        }
       $sql  .= $virgula." l20_formacontroleregistropreco = $this->l20_formacontroleregistropreco ";
       $virgula = ",";
     }
     if(trim($this->l20_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l20_tipo"])){
       $sql  .= $virgula." l20_tipo = $this->l20_tipo ";
       $virgula = ",";
       if(trim($this->l20_tipo) == null ){
         $this->erro_sql = " Campo Tipo não informado.";
         $this->erro_campo = "l20_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l20_codigo!=null){
       $sql .= " l20_codigo = $this->l20_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->l20_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,7589,'$this->l20_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_codigo"]) || $this->l20_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1260,7589,'".AddSlashes(pg_result($resaco,$conresaco,'l20_codigo'))."','$this->l20_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_codtipocom"]) || $this->l20_codtipocom != "")
             $resac = db_query("insert into db_acount values($acount,1260,7590,'".AddSlashes(pg_result($resaco,$conresaco,'l20_codtipocom'))."','$this->l20_codtipocom',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_numero"]) || $this->l20_numero != "")
             $resac = db_query("insert into db_acount values($acount,1260,7594,'".AddSlashes(pg_result($resaco,$conresaco,'l20_numero'))."','$this->l20_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_id_usucria"]) || $this->l20_id_usucria != "")
             $resac = db_query("insert into db_acount values($acount,1260,7592,'".AddSlashes(pg_result($resaco,$conresaco,'l20_id_usucria'))."','$this->l20_id_usucria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_datacria"]) || $this->l20_datacria != "")
             $resac = db_query("insert into db_acount values($acount,1260,7591,'".AddSlashes(pg_result($resaco,$conresaco,'l20_datacria'))."','$this->l20_datacria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_horacria"]) || $this->l20_horacria != "")
             $resac = db_query("insert into db_acount values($acount,1260,7593,'".AddSlashes(pg_result($resaco,$conresaco,'l20_horacria'))."','$this->l20_horacria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_dataaber"]) || $this->l20_dataaber != "")
             $resac = db_query("insert into db_acount values($acount,1260,7595,'".AddSlashes(pg_result($resaco,$conresaco,'l20_dataaber'))."','$this->l20_dataaber',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_dtpublic"]) || $this->l20_dtpublic != "")
             $resac = db_query("insert into db_acount values($acount,1260,7596,'".AddSlashes(pg_result($resaco,$conresaco,'l20_dtpublic'))."','$this->l20_dtpublic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_horaaber"]) || $this->l20_horaaber != "")
             $resac = db_query("insert into db_acount values($acount,1260,7597,'".AddSlashes(pg_result($resaco,$conresaco,'l20_horaaber'))."','$this->l20_horaaber',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_local"]) || $this->l20_local != "")
             $resac = db_query("insert into db_acount values($acount,1260,7598,'".AddSlashes(pg_result($resaco,$conresaco,'l20_local'))."','$this->l20_local',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_objeto"]) || $this->l20_objeto != "")
             $resac = db_query("insert into db_acount values($acount,1260,7599,'".AddSlashes(pg_result($resaco,$conresaco,'l20_objeto'))."','$this->l20_objeto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_tipojulg"]) || $this->l20_tipojulg != "")
             $resac = db_query("insert into db_acount values($acount,1260,7782,'".AddSlashes(pg_result($resaco,$conresaco,'l20_tipojulg'))."','$this->l20_tipojulg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_liccomissao"]) || $this->l20_liccomissao != "")
             $resac = db_query("insert into db_acount values($acount,1260,7909,'".AddSlashes(pg_result($resaco,$conresaco,'l20_liccomissao'))."','$this->l20_liccomissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_liclocal"]) || $this->l20_liclocal != "")
             $resac = db_query("insert into db_acount values($acount,1260,7908,'".AddSlashes(pg_result($resaco,$conresaco,'l20_liclocal'))."','$this->l20_liclocal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_procadmin"]) || $this->l20_procadmin != "")
             $resac = db_query("insert into db_acount values($acount,1260,8986,'".AddSlashes(pg_result($resaco,$conresaco,'l20_procadmin'))."','$this->l20_procadmin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_correto"]) || $this->l20_correto != "")
             $resac = db_query("insert into db_acount values($acount,1260,10010,'".AddSlashes(pg_result($resaco,$conresaco,'l20_correto'))."','$this->l20_correto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_instit"]) || $this->l20_instit != "")
             $resac = db_query("insert into db_acount values($acount,1260,10103,'".AddSlashes(pg_result($resaco,$conresaco,'l20_instit'))."','$this->l20_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_licsituacao"]) || $this->l20_licsituacao != "")
             $resac = db_query("insert into db_acount values($acount,1260,10287,'".AddSlashes(pg_result($resaco,$conresaco,'l20_licsituacao'))."','$this->l20_licsituacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_edital"]) || $this->l20_edital != "")
             $resac = db_query("insert into db_acount values($acount,1260,12605,'".AddSlashes(pg_result($resaco,$conresaco,'l20_edital'))."','$this->l20_edital',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_anousu"]) || $this->l20_anousu != "")
             $resac = db_query("insert into db_acount values($acount,1260,12606,'".AddSlashes(pg_result($resaco,$conresaco,'l20_anousu'))."','$this->l20_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_usaregistropreco"]) || $this->l20_usaregistropreco != "")
             $resac = db_query("insert into db_acount values($acount,1260,15270,'".AddSlashes(pg_result($resaco,$conresaco,'l20_usaregistropreco'))."','$this->l20_usaregistropreco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_localentrega"]) || $this->l20_localentrega != "")
             $resac = db_query("insert into db_acount values($acount,1260,15424,'".AddSlashes(pg_result($resaco,$conresaco,'l20_localentrega'))."','$this->l20_localentrega',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_prazoentrega"]) || $this->l20_prazoentrega != "")
             $resac = db_query("insert into db_acount values($acount,1260,15425,'".AddSlashes(pg_result($resaco,$conresaco,'l20_prazoentrega'))."','$this->l20_prazoentrega',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_condicoespag"]) || $this->l20_condicoespag != "")
             $resac = db_query("insert into db_acount values($acount,1260,15426,'".AddSlashes(pg_result($resaco,$conresaco,'l20_condicoespag'))."','$this->l20_condicoespag',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_validadeproposta"]) || $this->l20_validadeproposta != "")
             $resac = db_query("insert into db_acount values($acount,1260,15427,'".AddSlashes(pg_result($resaco,$conresaco,'l20_validadeproposta'))."','$this->l20_validadeproposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_formacontroleregistropreco"]) || $this->l20_formacontroleregistropreco != "")
             $resac = db_query("insert into db_acount values($acount,1260,20854,'".AddSlashes(pg_result($resaco,$conresaco,'l20_formacontroleregistropreco'))."','$this->l20_formacontroleregistropreco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l20_tipo"]) || $this->l20_tipo != "")
             $resac = db_query("insert into db_acount values($acount,1260,22362,'".AddSlashes(pg_result($resaco,$conresaco,'l20_tipo'))."','$this->l20_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "liclicita não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l20_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "liclicita não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l20_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->l20_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($l20_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($l20_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,7589,'$l20_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1260,7589,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,7590,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_codtipocom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,7594,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,7592,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_id_usucria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,7591,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_datacria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,7593,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_horacria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,7595,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_dataaber'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,7596,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_dtpublic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,7597,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_horaaber'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,7598,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_local'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,7599,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_objeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,7782,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_tipojulg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,7909,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_liccomissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,7908,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_liclocal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,8986,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_procadmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,10010,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_correto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,10103,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,10287,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_licsituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,12605,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_edital'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,12606,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,15270,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_usaregistropreco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,15424,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_localentrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,15425,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_prazoentrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,15426,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_condicoespag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,15427,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_validadeproposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,20854,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_formacontroleregistropreco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1260,22362,'','".AddSlashes(pg_result($resaco,$iresaco,'l20_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from liclicita
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($l20_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " l20_codigo = $l20_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "liclicita não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l20_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "liclicita não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l20_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$l20_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   public function sql_record($sql) {
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:liclicita";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($l20_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from liclicita ";
     $sql .= "      inner join db_config             on db_config.codigo = liclicita.l20_instit";
     $sql .= "      inner join db_usuarios           on db_usuarios.id_usuario = liclicita.l20_id_usucria";
     $sql .= "      inner join cflicita              on cflicita.l03_codigo = liclicita.l20_codtipocom";
     $sql .= "      inner join pctipocompratribunal  on pctipocompratribunal.l44_sequencial = cflicita.l03_pctipocompratribunal";
     $sql .= "      inner join liclocal              on liclocal.l26_codigo = liclicita.l20_liclocal";
     $sql .= "      inner join liccomissao           on liccomissao.l30_codigo = liclicita.l20_liccomissao";
     $sql .= "      inner join licsituacao           on licsituacao.l08_sequencial = liclicita.l20_licsituacao";
     $sql .= "      inner join cgm                   on cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_config as dbconfig on dbconfig.codigo = cflicita.l03_instit";
     $sql .= "      inner join pctipocompra          on pctipocompra.pc50_codcom = cflicita.l03_codcom";
     $sql .= "      inner join bairro                on bairro.j13_codi = liclocal.l26_bairro";
     $sql .= "      inner join ruas                  on ruas.j14_codigo = liclocal.l26_lograd";
     $sql .= "      left  join liclicitaproc         on liclicitaproc.l34_liclicita = liclicita.l20_codigo";
     $sql .= "      left  join protprocesso          on protprocesso.p58_codproc = liclicitaproc.l34_protprocesso";

     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($l20_codigo)) {
         $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql
   public function sql_query_file ($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from liclicita ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($l20_codigo)){
         $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

  function sql_query_baixa ( $l20_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from liclicita ";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liclicita.l20_id_usucria";
    $sql .= "      inner join cflicita  on  cflicita.l03_codigo = liclicita.l20_codtipocom";
    $sql .= "      inner join liclocal  on  liclocal.l26_codigo = liclicita.l20_liclocal";
    $sql .= "      inner join liccomissao  on  liccomissao.l30_codigo = liclicita.l20_liccomissao";
    $sql .= "      inner join db_config  on  db_config.codigo = cflicita.l03_instit";
    $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = cflicita.l03_codcom";
    $sql .= "      inner join bairro  on  bairro.j13_codi = liclocal.l26_bairro";
    $sql .= "      inner join ruas  on  ruas.j14_codigo = liclocal.l26_lograd";
    $sql .= "		inner join licbaixa on l20_codigo=l28_liclicita";
    $sql .= "      left join  liclicitaproc on liclicitaproc.l34_liclicita = liclicita.l20_codigo";
    $sql .= "      left join  protprocesso  on protprocesso.p58_codproc = liclicitaproc.l34_protprocesso";
    $sql2 = "";
    if($dbwhere==""){
      if($l20_codigo!=null ){
        $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  function sql_query_lib ( $l20_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from liclicita ";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liclicita.l20_id_usucria";
    $sql .= "      inner join cflicita  on  cflicita.l03_codigo = liclicita.l20_codtipocom";
    $sql .= "      inner join liclocal  on  liclocal.l26_codigo = liclicita.l20_liclocal";
    $sql .= "      inner join liccomissao  on  liccomissao.l30_codigo = liclicita.l20_liccomissao";
    $sql .= "      inner join db_config  on  db_config.codigo = cflicita.l03_instit";
    $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = cflicita.l03_codcom";
    $sql .= "      inner join bairro  on  bairro.j13_codi = liclocal.l26_bairro";
    $sql .= "      inner join ruas  on  ruas.j14_codigo = liclocal.l26_lograd";
    $sql .= "		left join liclicitaweb on l20_codigo=l29_liclicita";
    $sql2 = "";
    if($dbwhere==""){
      if($l20_codigo!=null ){
        $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  function sql_query_pco ( $l20_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from liclicita ";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liclicita.l20_id_usucria";
    $sql .= "      inner join cflicita  on  cflicita.l03_codigo = liclicita.l20_codtipocom";
    $sql .= "      inner join db_config  on  db_config.codigo = cflicita.l03_instit";
    $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = cflicita.l03_codcom";
    $sql .= "      inner join liclicitem on liclicitem.l21_codliclicita = liclicita.l20_codigo";
    $sql .= "      inner join pcorcamitemlic on pcorcamitemlic.pc26_liclicitem = liclicitem.l21_codigo";
    $sql .= "      inner join pcorcamitem on pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem";
    $sql .= "      inner join pcorcam on pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
    $sql2 = "";
    if($dbwhere==""){
      if($l20_codigo!=null ){
        $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_pcodireta ( $l20_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from pcorcam";
    $sql .= "      left join pcorcamitem on pcorcamitem.pc22_codorc = pc20_codorc";
    $sql .= "      left join pcorcamitemproc on pcorcamitemproc.pc31_orcamitem = pcorcamitem.pc22_orcamitem";
    $sql .= "      left join pcprocitem on pcorcamitemproc.pc31_pcprocitem = pc81_codprocitem";
    $sql .= "      left join pcorcamval on pc23_orcamitem = pc22_orcamitem";
    $sql .= "      left join pcorcamitemlic on pcorcamitemlic.pc26_orcamitem = pcorcamitemproc.pc31_orcamitem";
    $sql .= "      left join liclicitem on liclicitem.l21_codigo= pcorcamitemlic.pc26_liclicitem";
    $sql .= "      left join liclicita on liclicitem.l21_codliclicita= l20_codigo";
    $sql2 = "";
    if($dbwhere==""){
      if($l20_codigo!=null ){
        $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_modelos( $l20_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from liclicita ";
    $sql .= "      inner join cflicitatemplate     on cflicitatemplate.l35_cflicita        = liclicita.l20_codtipocom                 ";
    $sql .= "      inner join db_documentotemplate on db_documentotemplate.db82_sequencial = cflicitatemplate.l35_db_documentotemplate";

    $sql2 = "";
    if($dbwhere==""){
      if($l20_codigo!=null ){
        $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

   function sql_query_modelosatas( $l20_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from liclicita ";
     $sql .= "      inner join cflicitatemplateata  on cflicitatemplateata.l37_cflicita     = liclicita.l20_codtipocom                     ";
     $sql .= "      inner join db_documentotemplate on db_documentotemplate.db82_sequencial = cflicitatemplateata.l37_db_documentotemplate ";

     $sql2 = "";
     if($dbwhere==""){
       if($l20_codigo!=null ){
         $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }

  function sql_query_modelosminutas( $l20_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from liclicita ";
    $sql .= "      inner join cflicitatemplateminuta on cflicitatemplateminuta.l41_cflicita  = liclicita.l20_codtipocom                     ";
    $sql .= "      inner join db_documentotemplate   on db_documentotemplate.db82_sequencial = cflicitatemplateminuta.l41_db_documentotemplate ";

    $sql2 = "";
    if($dbwhere==""){
      if($l20_codigo!=null ){
        $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }


   function sql_query_julgamento_licitacao ( $l20_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

    $sql = "select ";
    if($campos != "*" ){

      $campos_sql = split("#",$campos);
      $virgula = "";

      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }

    $sql .= " from liclicita ";
    $sql .= "      inner join liclicitem               on liclicitem.l21_codliclicita         = liclicita.l20_codigo              ";
    $sql .= "      inner join pcprocitem               on pcprocitem.pc81_codprocitem         = liclicitem.l21_codpcprocitem      ";
    $sql .= "      inner join pcproc                   on pcproc.pc80_codproc                 = pcprocitem.pc81_codproc           ";
    $sql .= "      inner join solicitem                on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem         ";
    $sql .= "      inner join solicita                 on solicita.pc10_numero                = solicitem.pc11_numero             ";
    $sql .= "      inner join solicitempcmater         on solicitempcmater.pc16_solicitem     = solicitem.pc11_codigo             ";
    $sql .= "      inner join pcmater                  on pcmater.pc01_codmater               = solicitempcmater.pc16_codmater    ";
    $sql .= "      inner join pcorcamitemlic           on pcorcamitemlic.pc26_liclicitem      = liclicitem.l21_codigo             ";
    $sql .= "      inner join pcorcamval               on pcorcamval.pc23_orcamitem           = pcorcamitemlic.pc26_orcamitem     ";
    $sql .= "      inner join pcorcamforne             on pcorcamforne.pc21_orcamforne        = pcorcamval.pc23_orcamforne        ";
    $sql .= "      inner join pcorcamjulgamentologitem on pcorcamjulgamentologitem.pc93_pcorcamitem  = pcorcamval.pc23_orcamitem  ";
    $sql .= "                                         and pcorcamjulgamentologitem.pc93_pcorcamforne = pcorcamval.pc23_orcamforne	";
    $sql .= "      inner join pcorcamjulgamentolog     on pcorcamjulgamentolog.pc92_sequencial       = pcorcamjulgamentologitem.pc93_pcorcamjulgamentolog ";
    $sql .= "      inner join db_usuarios              on db_usuarios.id_usuario = pcorcamjulgamentolog.pc92_usuario   ";
    $sql .= "      inner join cgm as fornecedor        on fornecedor.z01_numcgm  = pcorcamforne.pc21_numcgm            ";

    $sql2 = "";
    if ($dbwhere == "") {

      if ($l20_codigo != null ) {
        $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {

      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;

  }


  // funcao do sql para trazer a observação da licitação
   function sql_query_dados_licitacao ( $l20_codigo=null,$campos="*",$ordem=null,$dbwhere="", $sSituacao = ''){


    $sCampo = "";

    if ($sSituacao != '') {

      $sSituacao = "and l11_licsituacao = {$sSituacao}";
      $sCampo    = ",liclicitasituacao.l11_obs";
    }

    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }

    $sql .= $sCampo;

    $sql .= " from liclicita ";
    $sql .= "      inner join db_config     on db_config.codigo = liclicita.l20_instit";
    $sql .= "      inner join db_usuarios   on db_usuarios.id_usuario = liclicita.l20_id_usucria";
    $sql .= "      inner join cflicita      on cflicita.l03_codigo = liclicita.l20_codtipocom";
    $sql .= "      inner join liclocal      on liclocal.l26_codigo = liclicita.l20_liclocal";
    $sql .= "      inner join liccomissao   on liccomissao.l30_codigo = liclicita.l20_liccomissao";
    $sql .= "      inner join licsituacao   on licsituacao.l08_sequencial = liclicita.l20_licsituacao";
    $sql .= "      inner join cgm           on  cgm.z01_numcgm = db_config.numcgm";
    $sql .= "      inner join db_config as dbconfig on  dbconfig.codigo = cflicita.l03_instit";
    $sql .= "      inner join pctipocompra  on pctipocompra.pc50_codcom = cflicita.l03_codcom";
    $sql .= "      inner join bairro        on bairro.j13_codi = liclocal.l26_bairro";
    $sql .= "      inner join ruas          on ruas.j14_codigo = liclocal.l26_lograd";
    $sql .= "      left  join liclicitaproc on liclicitaproc.l34_liclicita = liclicita.l20_codigo";
    $sql .= "      left  join protprocesso  on protprocesso.p58_codproc = liclicitaproc.l34_protprocesso";
    $sql .= "      inner join liclicitasituacao on liclicitasituacao.l11_liclicita = liclicita.l20_codigo $sSituacao";
    $sql .= "      left  join liclicitacadattdinamicovalorgrupo on l16_liclicita = l20_codigo ";
    $sql .= "      left  join pctipocompratribunal on l03_pctipocompratribunal = l44_sequencial ";

    $sql2 = "";
    if($dbwhere==""){
      if($l20_codigo!=null ){
        $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
      }
      }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
      }
      $sql .= $sql2;
      if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
      $sql .= $virgula.$campos_sql[$i];
      $virgula = ",";
      }
      }
      return $sql;
  }

  /**
   * query para chegar até o vinculo de contratos
   */
  function sql_queryContratos ( $l20_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from liclicita ";
    $sql .= "      inner join db_config             on db_config.codigo                    = liclicita.l20_instit";
    $sql .= "      inner join db_usuarios           on db_usuarios.id_usuario              = liclicita.l20_id_usucria";
    $sql .= "      inner join cflicita              on cflicita.l03_codigo                 = liclicita.l20_codtipocom";
    $sql .= "      inner join pctipocompratribunal  on pctipocompratribunal.l44_sequencial = cflicita.l03_pctipocompratribunal";
    $sql .= "      inner join liclocal              on liclocal.l26_codigo                 = liclicita.l20_liclocal";
    $sql .= "      inner join liccomissao           on liccomissao.l30_codigo              = liclicita.l20_liccomissao";
    $sql .= "      inner join licsituacao           on licsituacao.l08_sequencial          = liclicita.l20_licsituacao";
    $sql .= "      inner join cgm                   on cgm.z01_numcgm                      = db_config.numcgm";
    $sql .= "      inner join db_config as dbconfig on dbconfig.codigo                     = cflicita.l03_instit";
    $sql .= "      inner join pctipocompra          on pctipocompra.pc50_codcom            = cflicita.l03_codcom";
    $sql .= "      inner join bairro                on bairro.j13_codi                     = liclocal.l26_bairro";
    $sql .= "      inner join ruas                  on ruas.j14_codigo                     = liclocal.l26_lograd";
    $sql .= "       left join liclicitaproc         on liclicitaproc.l34_liclicita         = liclicita.l20_codigo";
    $sql .= "       left join protprocesso          on protprocesso.p58_codproc            = liclicitaproc.l34_protprocesso";
    $sql .= "       left join liclicitem            on liclicita.l20_codigo                = l21_codliclicita ";
    $sql .= "       left join acordoliclicitem      on liclicitem.l21_codigo               = acordoliclicitem.ac24_liclicitem ";

    $sql2 = "";
    if($dbwhere==""){
      if($l20_codigo!=null ){
        $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    //      echo $sql;
    return $sql;
  }

  function sql_query_licitacao_transparencia ( $l20_codigo=null,$campos="*",$ordem=null,$dbwhere="", $sSituacao = ''){


    $sCampo = "";

    if ($sSituacao != '') {

      $sSituacao = "and l11_licsituacao = {$sSituacao}";
      $sCampo    = ",liclicitasituacao.l11_obs";
      echo "teste: ".$sCampo;
    }

    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }

    $sql .= $sCampo;

    $sql .= " from liclicita ";
    $sql .= "      left join db_config     on db_config.codigo = liclicita.l20_instit";
    $sql .= "      left join cflicita      on cflicita.l03_codigo = liclicita.l20_codtipocom";
    $sql .= "      left join liclocal      on liclocal.l26_codigo = liclicita.l20_liclocal";
    $sql .= "      left join liccomissao   on liccomissao.l30_codigo = liclicita.l20_liccomissao";
    $sql .= "      left join licsituacao   on licsituacao.l08_sequencial = liclicita.l20_licsituacao";
    $sql .= "      left join cgm           on  cgm.z01_numcgm = db_config.numcgm";
    $sql .= "      left join pctipocompra  on pctipocompra.pc50_codcom = cflicita.l03_codcom";
    $sql .= "      left join bairro        on bairro.j13_codi = liclocal.l26_bairro";
    $sql .= "      left join ruas          on ruas.j14_codigo = liclocal.l26_lograd";
    $sql .= "      left  join liclicitaproc on liclicitaproc.l34_liclicita = liclicita.l20_codigo";
    $sql .= "      left  join protprocesso  on protprocesso.p58_codproc = liclicitaproc.l34_protprocesso";

    $sql2 = "";
    if($dbwhere==""){
      if($l20_codigo!=null ){
        $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }


  public function sql_query_licitantes($sCampos = null, $sWhere = null) {

    if (empty($sCampos)) {
      $sCampos = "*";
    }

    $sSqlBusca  = " select {$sCampos} ";
    $sSqlBusca .= "   from liclicita ";
    $sSqlBusca .= "        inner join cflicita on cflicita.l03_codigo = liclicita.l20_codtipocom ";
    $sSqlBusca .= "        inner join pctipocompratribunal on pctipocompratribunal.l44_sequencial = cflicita.l03_pctipocompratribunal ";
    $sSqlBusca .= "        inner join liclicitem on liclicitem.l21_codliclicita = liclicita.l20_codigo ";
    $sSqlBusca .= "        inner join pcorcamitemlic on pcorcamitemlic.pc26_liclicitem = liclicitem.l21_codigo ";
    $sSqlBusca .= "        inner join pcorcamitem on pcorcamitem.pc22_orcamitem = pcorcamitemlic.pc26_orcamitem ";
    $sSqlBusca .= "        inner join pcorcam     on pcorcamitem.pc22_codorc = pcorcam.pc20_codorc ";
    $sSqlBusca .= "        inner join pcorcamforne on pcorcamforne.pc21_codorc = pcorcam.pc20_codorc ";
    $sSqlBusca .= "        inner join pcorcamfornelic on pcorcamfornelic.pc31_orcamforne = pcorcamforne.pc21_orcamforne ";
    $sSqlBusca .= "        left join pcorcamfornelichabilitacao on l17_pcorcamfornelic = pc31_orcamforne";
    $sSqlBusca .= "        left join pcorcamjulg on pc24_orcamforne = pc21_orcamforne and pc24_orcamitem = pc22_orcamitem ";
    $sSqlBusca .= "        inner join cgm on cgm.z01_numcgm = pcorcamforne.pc21_numcgm ";
    $sSqlBusca .= "        left join liclicitaencerramentolicitacon on l18_liclicita  = l20_codigo ";
    $sSqlBusca .= "        left join liclicitaevento          on l46_liclicita = l20_codigo";
    $sSqlBusca .= "        left join cgm cgmevento            on cgmevento.z01_numcgm = liclicitaevento.l46_cgm ";
    $sSqlBusca .= "        left join liclicitaeventodocumento on l47_liclicitaevento = l46_sequencial ";

    if (!empty($sWhere)) {
      $sSqlBusca .= " where {$sWhere} ";
    }
    return $sSqlBusca;
  }

  function sql_query_licitacon($sCampos, $sWhere) {

    if (empty($sCampos)) {
      $sCampos = " * ";
    }

    $sSql  = " select {$sCampos} ";
    $sSql .= " from liclicita ";
    $sSql .= "      inner join cflicita                          on l20_codtipocom             = l03_codigo     ";
    $sSql .= "      inner join pctipocompratribunal              on l03_pctipocompratribunal   = l44_sequencial ";
    $sSql .= "      inner join liccomissao                       on l20_liccomissao            = l30_codigo     ";
    $sSql .= "      inner join licsituacao                       on l20_licsituacao            = l08_sequencial ";
    $sSql .= "      left  join liclicitaproc                     on l34_liclicita              = l20_codigo     ";
    $sSql .= "      left  join protprocesso                      on l34_protprocesso           = p58_codproc    ";
    $sSql .= "      left  join liclicitacadattdinamicovalorgrupo on l16_liclicita              = l20_codigo     ";
    $sSql .= "      left  join liclicitaevento                   on l46_liclicita              = l20_codigo     ";
    $sSql .= "      left  join liclicitaencerramentolicitacon    on l18_liclicita              = l20_codigo     ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    return $sSql;
  }

  public function sql_query_eventos_documentos($sCampos, $sWhere) {

    if (empty($sCampos)) {
      $sCampos = " * ";
    }

    $sSql  = " select {$sCampos}                                                                       ";
    $sSql .= " from liclicita                                                                          ";
    $sSql .= "      inner join cflicita                 on l20_codtipocom             = l03_codigo     ";
    $sSql .= "      inner join pctipocompratribunal     on l03_pctipocompratribunal   = l44_sequencial ";
    $sSql .= "      inner join liclicitaevento          on l46_liclicita              = l20_codigo     ";
    $sSql .= "      inner join liclicitaeventodocumento on l47_liclicitaevento        = l46_sequencial ";
    $sSql .= "      left  join cgm                      on l46_cgm                    = z01_numcgm     ";
    $sSql .= "      left  join liclicitaencerramentolicitacon on l18_liclicita = l20_codigo ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    return $sSql;
  }

  function sql_query_licitacon_dotacao_lic($sCampos, $sWhere) {

    if (empty($sCampos)) {
      $sCampos = " * ";
    }

    $sSql  = " select {$sCampos}                                                                             ";
    $sSql .= " from liclicitem                                                                                ";
    $sSql .= "      inner join liclicita                     on l21_codliclicita = l20_codigo                         ";
    $sSql .= "      inner join cflicita                      on l20_codtipocom = l03_codigo                           ";
    $sSql .= "      inner join pctipocompratribunal          on l03_pctipocompratribunal = l44_sequencial             ";
    $sSql .= "      inner join pcprocitem                    on l21_codpcprocitem = pc81_codprocitem                  ";
    $sSql .= "      inner join solicitem                     on pc81_solicitem = pc11_codigo                          ";
    $sSql .= "      inner join pcdotac                       on pc11_codigo = pc13_codigo                             ";
    $sSql .= "      inner join orcdotacao                    on (pc13_coddot, pc13_anousu) = (o58_coddot, o58_anousu) ";
    $sSql .= "      inner join orcelemento                   on o58_codele = o56_codele                               ";
    $sSql .= "      left  join liclicitaevento               on l46_liclicita = l20_codigo                            ";
    $sSql .= "      left join liclicitaencerramentolicitacon on l18_liclicita = l20_codigo                            ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    return $sSql;
  }

  function sql_query_licitacao_comissao($sCampos, $sWhere) {

    if (empty($sCampos)) {
      $sCampos = " * ";
    }

    $sSql  = " select {$sCampos}                                                             ";
    $sSql .= " from liccomissaocgm                                                           ";
    $sSql .= "     inner join liccomissao                    on l31_liccomissao = l30_codigo ";
    $sSql .= "     inner join liclicita                      on l20_liccomissao = l30_codigo ";
    $sSql .= "     left  join liclicitaencerramentolicitacon on l18_liclicita   = l20_codigo ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    return $sSql;
  }

  function sql_query_encerramento($sCampos, $sWhere) {

    if (empty($sCampos)) {
      $sCampos = " * ";
    }

    $sSql  = " select {$sCampos}  ";
    $sSql .= " from liclicita     ";
    $sSql .= "     inner join liclicitaevento                on l46_liclicita  = l20_codigo       ";
    $sSql .= "     left  join liclicitaencerramentolicitacon on l18_liclicita  = l20_codigo       ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    return $sSql;
  }

  function sql_query_licitacao_encerramento($sCampos = '*', $sWhere = '') {

    $sSql  = " select {$sCampos}  ";
    $sSql .= " from liclicita     ";
    $sSql .= " inner join cflicita             on l20_codtipocom           = l03_codigo ";
    $sSql .= " inner join pctipocompratribunal on l03_pctipocompratribunal = l44_sequencial ";
    $sSql .= " left  join liclicitaencerramentolicitacon on l18_liclicita  = l20_codigo       ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }

    return $sSql;
  }

  function sql_query_propostas($sCampos = null, $sWhere = null, $sGroupBy = null, $sOrderBy = null) {
    if (empty($sCampos)) {
      $sCampos = "*";
    }

    $sSql  = "select {$sCampos}";
    $sSql .= " from liclicita ";
    $sSql .= "  inner join cflicita                       on cflicita.l03_codigo = liclicita.l20_codtipocom ";
    $sSql .= "  inner join pctipocompratribunal           on pctipocompratribunal.l44_sequencial  = cflicita.l03_pctipocompratribunal ";
    $sSql .= "  inner join liclicitasituacao              on liclicitasituacao.l11_liclicita = liclicita.l20_codigo ";
    $sSql .= "  inner join liclicitem                     on liclicitem.l21_codliclicita = liclicita.l20_codigo ";
    $sSql .= "  inner join liclicitemlote                 on liclicitemlote.l04_liclicitem = liclicitem.l21_codigo  ";
    $sSql .= "  inner join pcorcamitemlic                 on pcorcamitemlic.pc26_liclicitem = liclicitem.l21_codigo ";
    $sSql .= "  inner join pcorcamitem                    on pcorcamitem.pc22_orcamitem = pcorcamitemlic.pc26_orcamitem ";
    $sSql .= "  inner join pcorcamforne         on pcorcamforne.pc21_codorc = pcorcamitem.pc22_codorc ";
    $sSql .= "  inner join pcorcamfornelic            on pc31_orcamforne = pc21_orcamforne";
    $sSql .= "  left  join pcorcamfornelichabilitacao on l17_pcorcamfornelic = pc31_orcamforne";
    $sSql .= "  left  join pcorcamval            on pcorcamval.pc23_orcamforne = pcorcamforne.pc21_orcamforne and pcorcamval.pc23_orcamitem = pcorcamitem.pc22_orcamitem ";
    $sSql .= "  left join  pcorcamdescla                  on pcorcamdescla.pc32_orcamitem = pcorcamval.pc23_orcamitem and pcorcamdescla.pc32_orcamforne = pcorcamval.pc23_orcamforne";
    $sSql .= "  left join cgm                   on cgm.z01_numcgm = pcorcamforne.pc21_numcgm ";
    $sSql .= "  left join pcorcamjulg                     on pcorcamjulg.pc24_orcamitem = pcorcamitem.pc22_orcamitem ";
    $sSql .= "  left join  liclicitaencerramentolicitacon on liclicitaencerramentolicitacon.l18_liclicita = liclicita.l20_codigo ";
    $sSql .= "  left join liclicitacadattdinamicovalorgrupo  on liclicitacadattdinamicovalorgrupo.l16_liclicita  = liclicita.l20_codigo ";

    if (!empty($sWhere)) {
      $sSql .= "where {$sWhere} ";
    }

    if (!empty($sGroupBy)) {
      $sSql .= "group by {$sGroupBy} ";
    }

    if (!empty($sOrderBy)) {
      $sSql .= "order by {$sOrderBy} ";
    }

    return $sSql;
  }

  public function sql_query_lote($sCampos, $sWhere, $sGroupBy, $sOrderBy) {

    if (empty($sCampos)) {
      $sCampos = "*";
    }

    $sSql  = "select {$sCampos} ";
    $sSql .= "from liclicita ";
    $sSql .= "     inner join cflicita                on l20_codtipocom           = l03_codigo ";
    $sSql .= "     inner join pctipocompratribunal    on l03_pctipocompratribunal = l44_sequencial ";
    $sSql .= "     inner join liclicitem              on l21_codliclicita         = l20_codigo ";
    $sSql .= "     left join  liclicitemlote          on l04_liclicitem           = l21_codigo ";
    $sSql .= "     left join  pcprocitem              on l21_codpcprocitem        = pc81_codprocitem ";
    $sSql .= "     left join  processocompraloteitem  on pc69_pcprocitem          = pc81_codprocitem ";
    $sSql .= "     left join  processocompralote      on pc68_sequencial          = pc69_processocompralote ";
    $sSql .= "     left join  liclicitaencerramentolicitacon on l18_liclicita    = l20_codigo ";
    $sSql .= "     left join  pcorcamitemlic          on l21_codigo      = pc26_liclicitem ";
    $sSql .= "     left join  pcorcamitem             on pc22_orcamitem  = pc26_orcamitem ";
    $sSql .= "     left join  pcorcamjulg             on pc24_orcamitem  = pc22_orcamitem ";

    if (!empty($sWhere)) {
      $sSql .= "where {$sWhere} ";
    }

    if (!empty($sGroupBy)) {
      $sSql .= "group by {$sGroupBy} ";
    }

    if (!empty($sOrderBy)) {
      $sSql .= "order by {$sOrderBy} ";
    }

    return $sSql;
  }
}
