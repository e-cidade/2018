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

//MODULO: divida
//CLASSE DA ENTIDADE divida
class cl_divida {
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
   var $v01_coddiv = 0;
   var $v01_numcgm = 0;
   var $v01_dtinsc_dia = null;
   var $v01_dtinsc_mes = null;
   var $v01_dtinsc_ano = null;
   var $v01_dtinsc = null;
   var $v01_exerc = 0;
   var $v01_numpre = 0;
   var $v01_numpar = 0;
   var $v01_numtot = 0;
   var $v01_vlrhis = 0;
   var $v01_proced = 0;
   var $v01_livro = 0;
   var $v01_folha = 0;
   var $v01_dtvenc_dia = null;
   var $v01_dtvenc_mes = null;
   var $v01_dtvenc_ano = null;
   var $v01_dtvenc = null;
   var $v01_dtoper_dia = null;
   var $v01_dtoper_mes = null;
   var $v01_dtoper_ano = null;
   var $v01_dtoper = null;
   var $v01_valor = 0;
   var $v01_obs = null;
   var $v01_numdig = 0;
   var $v01_instit = 0;
   var $v01_dtinclusao_dia = null;
   var $v01_dtinclusao_mes = null;
   var $v01_dtinclusao_ano = null;
   var $v01_dtinclusao = null;
   var $v01_processo = null;
   var $v01_titular = null;
   var $v01_dtprocesso_dia = null;
   var $v01_dtprocesso_mes = null;
   var $v01_dtprocesso_ano = null;
   var $v01_dtprocesso = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 v01_coddiv = int4 = codigo da divida
                 v01_numcgm = int4 = numero do cgm
                 v01_dtinsc = date = data de inscricao
                 v01_exerc = int4 = exercicio da divida
                 v01_numpre = int4 = numpre
                 v01_numpar = int4 = Parcela
                 v01_numtot = int4 = numtot
                 v01_vlrhis = float8 = valor historico
                 v01_proced = int4 = procedencia
                 v01_livro = int4 = livro
                 v01_folha = int4 = folha
                 v01_dtvenc = date = data de vencimento
                 v01_dtoper = date = data de operacao
                 v01_valor = float8 = valor
                 v01_obs = text = observacoes
                 v01_numdig = int4 = numdig
                 v01_instit = int4 = Cod. Instituição
                 v01_dtinclusao = date = Data Inclusão
                 v01_processo = varchar(150) = Código do processo
                 v01_titular = varchar(150) = Titular do Processo
                 v01_dtprocesso = date = Data do Processo
                 ";
   //funcao construtor da classe
   function cl_divida() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("divida");
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
       $this->v01_coddiv = ($this->v01_coddiv == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_coddiv"]:$this->v01_coddiv);
       $this->v01_numcgm = ($this->v01_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_numcgm"]:$this->v01_numcgm);
       if($this->v01_dtinsc == ""){
         $this->v01_dtinsc_dia = ($this->v01_dtinsc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_dtinsc_dia"]:$this->v01_dtinsc_dia);
         $this->v01_dtinsc_mes = ($this->v01_dtinsc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_dtinsc_mes"]:$this->v01_dtinsc_mes);
         $this->v01_dtinsc_ano = ($this->v01_dtinsc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_dtinsc_ano"]:$this->v01_dtinsc_ano);
         if($this->v01_dtinsc_dia != ""){
            $this->v01_dtinsc = $this->v01_dtinsc_ano."-".$this->v01_dtinsc_mes."-".$this->v01_dtinsc_dia;
         }
       }
       $this->v01_exerc = ($this->v01_exerc == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_exerc"]:$this->v01_exerc);
       $this->v01_numpre = ($this->v01_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_numpre"]:$this->v01_numpre);
       $this->v01_numpar = ($this->v01_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_numpar"]:$this->v01_numpar);
       $this->v01_numtot = ($this->v01_numtot == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_numtot"]:$this->v01_numtot);
       $this->v01_vlrhis = ($this->v01_vlrhis == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_vlrhis"]:$this->v01_vlrhis);
       $this->v01_proced = ($this->v01_proced == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_proced"]:$this->v01_proced);
       $this->v01_livro = ($this->v01_livro == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_livro"]:$this->v01_livro);
       $this->v01_folha = ($this->v01_folha == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_folha"]:$this->v01_folha);
       if($this->v01_dtvenc == ""){
         $this->v01_dtvenc_dia = ($this->v01_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_dtvenc_dia"]:$this->v01_dtvenc_dia);
         $this->v01_dtvenc_mes = ($this->v01_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_dtvenc_mes"]:$this->v01_dtvenc_mes);
         $this->v01_dtvenc_ano = ($this->v01_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_dtvenc_ano"]:$this->v01_dtvenc_ano);
         if($this->v01_dtvenc_dia != ""){
            $this->v01_dtvenc = $this->v01_dtvenc_ano."-".$this->v01_dtvenc_mes."-".$this->v01_dtvenc_dia;
         }
       }
       if($this->v01_dtoper == ""){
         $this->v01_dtoper_dia = ($this->v01_dtoper_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_dtoper_dia"]:$this->v01_dtoper_dia);
         $this->v01_dtoper_mes = ($this->v01_dtoper_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_dtoper_mes"]:$this->v01_dtoper_mes);
         $this->v01_dtoper_ano = ($this->v01_dtoper_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_dtoper_ano"]:$this->v01_dtoper_ano);
         if($this->v01_dtoper_dia != ""){
            $this->v01_dtoper = $this->v01_dtoper_ano."-".$this->v01_dtoper_mes."-".$this->v01_dtoper_dia;
         }
       }
       $this->v01_valor = ($this->v01_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_valor"]:$this->v01_valor);
       $this->v01_obs = ($this->v01_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_obs"]:$this->v01_obs);
       $this->v01_numdig = ($this->v01_numdig == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_numdig"]:$this->v01_numdig);
       $this->v01_instit = ($this->v01_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_instit"]:$this->v01_instit);
       if($this->v01_dtinclusao == ""){
         $this->v01_dtinclusao_dia = ($this->v01_dtinclusao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_dtinclusao_dia"]:$this->v01_dtinclusao_dia);
         $this->v01_dtinclusao_mes = ($this->v01_dtinclusao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_dtinclusao_mes"]:$this->v01_dtinclusao_mes);
         $this->v01_dtinclusao_ano = ($this->v01_dtinclusao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_dtinclusao_ano"]:$this->v01_dtinclusao_ano);
         if($this->v01_dtinclusao_dia != ""){
            $this->v01_dtinclusao = $this->v01_dtinclusao_ano."-".$this->v01_dtinclusao_mes."-".$this->v01_dtinclusao_dia;
         }
       }
       $this->v01_processo = ($this->v01_processo == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_processo"]:$this->v01_processo);
       $this->v01_titular = ($this->v01_titular == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_titular"]:$this->v01_titular);
       if($this->v01_dtprocesso == ""){
         $this->v01_dtprocesso_dia = ($this->v01_dtprocesso_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_dtprocesso_dia"]:$this->v01_dtprocesso_dia);
         $this->v01_dtprocesso_mes = ($this->v01_dtprocesso_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_dtprocesso_mes"]:$this->v01_dtprocesso_mes);
         $this->v01_dtprocesso_ano = ($this->v01_dtprocesso_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_dtprocesso_ano"]:$this->v01_dtprocesso_ano);
         if($this->v01_dtprocesso_dia != ""){
            $this->v01_dtprocesso = $this->v01_dtprocesso_ano."-".$this->v01_dtprocesso_mes."-".$this->v01_dtprocesso_dia;
         }
       }
     }else{
       $this->v01_coddiv = ($this->v01_coddiv == ""?@$GLOBALS["HTTP_POST_VARS"]["v01_coddiv"]:$this->v01_coddiv);
     }
   }
   // funcao para inclusao
   function incluir ($v01_coddiv){
      $this->atualizacampos();
     if($this->v01_numcgm == null ){
       $this->erro_sql = " Campo numero do cgm nao Informado.";
       $this->erro_campo = "v01_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v01_dtinsc == null ){
       $this->erro_sql = " Campo data de inscricao nao Informado.";
       $this->erro_campo = "v01_dtinsc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v01_exerc == null ){
       $this->erro_sql = " Campo exercicio da divida nao Informado.";
       $this->erro_campo = "v01_exerc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v01_numpre == null ){
       $this->erro_sql = " Campo numpre nao Informado.";
       $this->erro_campo = "v01_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v01_numpar == null ){
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "v01_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v01_numtot == null ){
       $this->erro_sql = " Campo numtot nao Informado.";
       $this->erro_campo = "v01_numtot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v01_vlrhis == null ){
       $this->erro_sql = " Campo valor historico nao Informado.";
       $this->erro_campo = "v01_vlrhis";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v01_proced == null ){
       $this->erro_sql = " Campo procedencia nao Informado.";
       $this->erro_campo = "v01_proced";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v01_livro == null ){
       $this->v01_livro = "0";
     }
     if($this->v01_folha == null ){
       $this->v01_folha = "0";
     }
     if($this->v01_dtvenc == null ){
       $this->erro_sql = " Campo data de vencimento nao Informado.";
       $this->erro_campo = "v01_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v01_dtoper == null ){
       $this->erro_sql = " Campo data de operacao nao Informado.";
       $this->erro_campo = "v01_dtoper_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v01_valor == null ){
       $this->erro_sql = " Campo valor nao Informado.";
       $this->erro_campo = "v01_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v01_numdig == null ){
       $this->erro_sql = " Campo numdig nao Informado.";
       $this->erro_campo = "v01_numdig";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v01_instit == null ){
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "v01_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v01_dtinclusao == null ){
       $this->erro_sql = " Campo Data Inclusão nao Informado.";
       $this->erro_campo = "v01_dtinclusao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v01_dtprocesso == null ){
       $this->v01_dtprocesso = "null";
     }
     if($v01_coddiv == "" || $v01_coddiv == null ){
       $result = db_query("select nextval('divida_v01_coddiv_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: divida_v01_coddiv_seq do campo: v01_coddiv";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->v01_coddiv = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from divida_v01_coddiv_seq");
       if(($result != false) && (pg_result($result,0,0) < $v01_coddiv)){
         $this->erro_sql = " Campo v01_coddiv maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v01_coddiv = $v01_coddiv;
       }
     }
     if(($this->v01_coddiv == null) || ($this->v01_coddiv == "") ){
       $this->erro_sql = " Campo v01_coddiv nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into divida(
                                       v01_coddiv
                                      ,v01_numcgm
                                      ,v01_dtinsc
                                      ,v01_exerc
                                      ,v01_numpre
                                      ,v01_numpar
                                      ,v01_numtot
                                      ,v01_vlrhis
                                      ,v01_proced
                                      ,v01_livro
                                      ,v01_folha
                                      ,v01_dtvenc
                                      ,v01_dtoper
                                      ,v01_valor
                                      ,v01_obs
                                      ,v01_numdig
                                      ,v01_instit
                                      ,v01_dtinclusao
                                      ,v01_processo
                                      ,v01_titular
                                      ,v01_dtprocesso
                       )
                values (
                                $this->v01_coddiv
                               ,$this->v01_numcgm
                               ,".($this->v01_dtinsc == "null" || $this->v01_dtinsc == ""?"null":"'".$this->v01_dtinsc."'")."
                               ,$this->v01_exerc
                               ,$this->v01_numpre
                               ,$this->v01_numpar
                               ,$this->v01_numtot
                               ,$this->v01_vlrhis
                               ,$this->v01_proced
                               ,$this->v01_livro
                               ,$this->v01_folha
                               ,".($this->v01_dtvenc == "null" || $this->v01_dtvenc == ""?"null":"'".$this->v01_dtvenc."'")."
                               ,".($this->v01_dtoper == "null" || $this->v01_dtoper == ""?"null":"'".$this->v01_dtoper."'")."
                               ,$this->v01_valor
                               ,'$this->v01_obs'
                               ,$this->v01_numdig
                               ,$this->v01_instit
                               ,".($this->v01_dtinclusao == "null" || $this->v01_dtinclusao == ""?"null":"'".$this->v01_dtinclusao."'")."
                               ,'$this->v01_processo'
                               ,'$this->v01_titular'
                               ,".($this->v01_dtprocesso == "null" || $this->v01_dtprocesso == ""?"null":"'".$this->v01_dtprocesso."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->v01_coddiv) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->v01_coddiv) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v01_coddiv;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v01_coddiv));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,508,'$this->v01_coddiv','I')");
       $resac = db_query("insert into db_acount values($acount,96,508,'','".AddSlashes(pg_result($resaco,0,'v01_coddiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,509,'','".AddSlashes(pg_result($resaco,0,'v01_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,510,'','".AddSlashes(pg_result($resaco,0,'v01_dtinsc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,511,'','".AddSlashes(pg_result($resaco,0,'v01_exerc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,512,'','".AddSlashes(pg_result($resaco,0,'v01_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,513,'','".AddSlashes(pg_result($resaco,0,'v01_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,514,'','".AddSlashes(pg_result($resaco,0,'v01_numtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,516,'','".AddSlashes(pg_result($resaco,0,'v01_vlrhis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,517,'','".AddSlashes(pg_result($resaco,0,'v01_proced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,519,'','".AddSlashes(pg_result($resaco,0,'v01_livro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,520,'','".AddSlashes(pg_result($resaco,0,'v01_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,521,'','".AddSlashes(pg_result($resaco,0,'v01_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,522,'','".AddSlashes(pg_result($resaco,0,'v01_dtoper'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,523,'','".AddSlashes(pg_result($resaco,0,'v01_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,518,'','".AddSlashes(pg_result($resaco,0,'v01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,515,'','".AddSlashes(pg_result($resaco,0,'v01_numdig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,10572,'','".AddSlashes(pg_result($resaco,0,'v01_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,15195,'','".AddSlashes(pg_result($resaco,0,'v01_dtinclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,18801,'','".AddSlashes(pg_result($resaco,0,'v01_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,18802,'','".AddSlashes(pg_result($resaco,0,'v01_titular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,96,18803,'','".AddSlashes(pg_result($resaco,0,'v01_dtprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($v01_coddiv=null) {
      $this->atualizacampos();
     $sql = " update divida set ";
     $virgula = "";
     if(trim($this->v01_coddiv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_coddiv"])){
       $sql  .= $virgula." v01_coddiv = $this->v01_coddiv ";
       $virgula = ",";
       if(trim($this->v01_coddiv) == null ){
         $this->erro_sql = " Campo codigo da divida nao Informado.";
         $this->erro_campo = "v01_coddiv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v01_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_numcgm"])){
       $sql  .= $virgula." v01_numcgm = $this->v01_numcgm ";
       $virgula = ",";
       if(trim($this->v01_numcgm) == null ){
         $this->erro_sql = " Campo numero do cgm nao Informado.";
         $this->erro_campo = "v01_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v01_dtinsc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_dtinsc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v01_dtinsc_dia"] !="") ){
       $sql  .= $virgula." v01_dtinsc = '$this->v01_dtinsc' ";
       $virgula = ",";
       if(trim($this->v01_dtinsc) == null ){
         $this->erro_sql = " Campo data de inscricao nao Informado.";
         $this->erro_campo = "v01_dtinsc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v01_dtinsc_dia"])){
         $sql  .= $virgula." v01_dtinsc = null ";
         $virgula = ",";
         if(trim($this->v01_dtinsc) == null ){
           $this->erro_sql = " Campo data de inscricao nao Informado.";
           $this->erro_campo = "v01_dtinsc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v01_exerc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_exerc"])){
       $sql  .= $virgula." v01_exerc = $this->v01_exerc ";
       $virgula = ",";
       if(trim($this->v01_exerc) == null ){
         $this->erro_sql = " Campo exercicio da divida nao Informado.";
         $this->erro_campo = "v01_exerc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v01_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_numpre"])){
       $sql  .= $virgula." v01_numpre = $this->v01_numpre ";
       $virgula = ",";
       if(trim($this->v01_numpre) == null ){
         $this->erro_sql = " Campo numpre nao Informado.";
         $this->erro_campo = "v01_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v01_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_numpar"])){
       $sql  .= $virgula." v01_numpar = $this->v01_numpar ";
       $virgula = ",";
       if(trim($this->v01_numpar) == null ){
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "v01_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v01_numtot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_numtot"])){
       $sql  .= $virgula." v01_numtot = $this->v01_numtot ";
       $virgula = ",";
       if(trim($this->v01_numtot) == null ){
         $this->erro_sql = " Campo numtot nao Informado.";
         $this->erro_campo = "v01_numtot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v01_vlrhis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_vlrhis"])){
       $sql  .= $virgula." v01_vlrhis = $this->v01_vlrhis ";
       $virgula = ",";
       if(trim($this->v01_vlrhis) == null ){
         $this->erro_sql = " Campo valor historico nao Informado.";
         $this->erro_campo = "v01_vlrhis";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v01_proced)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_proced"])){
       $sql  .= $virgula." v01_proced = $this->v01_proced ";
       $virgula = ",";
       if(trim($this->v01_proced) == null ){
         $this->erro_sql = " Campo procedencia nao Informado.";
         $this->erro_campo = "v01_proced";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v01_livro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_livro"])){
        if(trim($this->v01_livro)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v01_livro"])){
           $this->v01_livro = "0" ;
        }
       $sql  .= $virgula." v01_livro = $this->v01_livro ";
       $virgula = ",";
     }
     if(trim($this->v01_folha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_folha"])){
        if(trim($this->v01_folha)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v01_folha"])){
           $this->v01_folha = "0" ;
        }
       $sql  .= $virgula." v01_folha = $this->v01_folha ";
       $virgula = ",";
     }
     if(trim($this->v01_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v01_dtvenc_dia"] !="") ){
       $sql  .= $virgula." v01_dtvenc = '$this->v01_dtvenc' ";
       $virgula = ",";
       if(trim($this->v01_dtvenc) == null ){
         $this->erro_sql = " Campo data de vencimento nao Informado.";
         $this->erro_campo = "v01_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v01_dtvenc_dia"])){
         $sql  .= $virgula." v01_dtvenc = null ";
         $virgula = ",";
         if(trim($this->v01_dtvenc) == null ){
           $this->erro_sql = " Campo data de vencimento nao Informado.";
           $this->erro_campo = "v01_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v01_dtoper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_dtoper_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v01_dtoper_dia"] !="") ){
       $sql  .= $virgula." v01_dtoper = '$this->v01_dtoper' ";
       $virgula = ",";
       if(trim($this->v01_dtoper) == null ){
         $this->erro_sql = " Campo data de operacao nao Informado.";
         $this->erro_campo = "v01_dtoper_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v01_dtoper_dia"])){
         $sql  .= $virgula." v01_dtoper = null ";
         $virgula = ",";
         if(trim($this->v01_dtoper) == null ){
           $this->erro_sql = " Campo data de operacao nao Informado.";
           $this->erro_campo = "v01_dtoper_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v01_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_valor"])){
       $sql  .= $virgula." v01_valor = $this->v01_valor ";
       $virgula = ",";
       if(trim($this->v01_valor) == null ){
         $this->erro_sql = " Campo valor nao Informado.";
         $this->erro_campo = "v01_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v01_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_obs"])){
       $sql  .= $virgula." v01_obs = '$this->v01_obs' ";
       $virgula = ",";
     }
     if(trim($this->v01_numdig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_numdig"])){
       $sql  .= $virgula." v01_numdig = $this->v01_numdig ";
       $virgula = ",";
       if(trim($this->v01_numdig) == null ){
         $this->erro_sql = " Campo numdig nao Informado.";
         $this->erro_campo = "v01_numdig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v01_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_instit"])){
       $sql  .= $virgula." v01_instit = $this->v01_instit ";
       $virgula = ",";
       if(trim($this->v01_instit) == null ){
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "v01_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v01_dtinclusao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_dtinclusao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v01_dtinclusao_dia"] !="") ){
       $sql  .= $virgula." v01_dtinclusao = '$this->v01_dtinclusao' ";
       $virgula = ",";
       if(trim($this->v01_dtinclusao) == null ){
         $this->erro_sql = " Campo Data Inclusão nao Informado.";
         $this->erro_campo = "v01_dtinclusao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v01_dtinclusao_dia"])){
         $sql  .= $virgula." v01_dtinclusao = null ";
         $virgula = ",";
         if(trim($this->v01_dtinclusao) == null ){
           $this->erro_sql = " Campo Data Inclusão nao Informado.";
           $this->erro_campo = "v01_dtinclusao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v01_processo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_processo"])){
       $sql  .= $virgula." v01_processo = '$this->v01_processo' ";
       $virgula = ",";
     }
     if(trim($this->v01_titular)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_titular"])){
       $sql  .= $virgula." v01_titular = '$this->v01_titular' ";
       $virgula = ",";
     }
     if(trim($this->v01_dtprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v01_dtprocesso_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v01_dtprocesso_dia"] !="") ){
       $sql  .= $virgula." v01_dtprocesso = '$this->v01_dtprocesso' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v01_dtprocesso_dia"])){
         $sql  .= $virgula." v01_dtprocesso = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($v01_coddiv!=null){
       $sql .= " v01_coddiv = $this->v01_coddiv";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v01_coddiv));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,508,'$this->v01_coddiv','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_coddiv"]) || $this->v01_coddiv != "")
           $resac = db_query("insert into db_acount values($acount,96,508,'".AddSlashes(pg_result($resaco,$conresaco,'v01_coddiv'))."','$this->v01_coddiv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_numcgm"]) || $this->v01_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,96,509,'".AddSlashes(pg_result($resaco,$conresaco,'v01_numcgm'))."','$this->v01_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_dtinsc"]) || $this->v01_dtinsc != "")
           $resac = db_query("insert into db_acount values($acount,96,510,'".AddSlashes(pg_result($resaco,$conresaco,'v01_dtinsc'))."','$this->v01_dtinsc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_exerc"]) || $this->v01_exerc != "")
           $resac = db_query("insert into db_acount values($acount,96,511,'".AddSlashes(pg_result($resaco,$conresaco,'v01_exerc'))."','$this->v01_exerc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_numpre"]) || $this->v01_numpre != "")
           $resac = db_query("insert into db_acount values($acount,96,512,'".AddSlashes(pg_result($resaco,$conresaco,'v01_numpre'))."','$this->v01_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_numpar"]) || $this->v01_numpar != "")
           $resac = db_query("insert into db_acount values($acount,96,513,'".AddSlashes(pg_result($resaco,$conresaco,'v01_numpar'))."','$this->v01_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_numtot"]) || $this->v01_numtot != "")
           $resac = db_query("insert into db_acount values($acount,96,514,'".AddSlashes(pg_result($resaco,$conresaco,'v01_numtot'))."','$this->v01_numtot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_vlrhis"]) || $this->v01_vlrhis != "")
           $resac = db_query("insert into db_acount values($acount,96,516,'".AddSlashes(pg_result($resaco,$conresaco,'v01_vlrhis'))."','$this->v01_vlrhis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_proced"]) || $this->v01_proced != "")
           $resac = db_query("insert into db_acount values($acount,96,517,'".AddSlashes(pg_result($resaco,$conresaco,'v01_proced'))."','$this->v01_proced',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_livro"]) || $this->v01_livro != "")
           $resac = db_query("insert into db_acount values($acount,96,519,'".AddSlashes(pg_result($resaco,$conresaco,'v01_livro'))."','$this->v01_livro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_folha"]) || $this->v01_folha != "")
           $resac = db_query("insert into db_acount values($acount,96,520,'".AddSlashes(pg_result($resaco,$conresaco,'v01_folha'))."','$this->v01_folha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_dtvenc"]) || $this->v01_dtvenc != "")
           $resac = db_query("insert into db_acount values($acount,96,521,'".AddSlashes(pg_result($resaco,$conresaco,'v01_dtvenc'))."','$this->v01_dtvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_dtoper"]) || $this->v01_dtoper != "")
           $resac = db_query("insert into db_acount values($acount,96,522,'".AddSlashes(pg_result($resaco,$conresaco,'v01_dtoper'))."','$this->v01_dtoper',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_valor"]) || $this->v01_valor != "")
           $resac = db_query("insert into db_acount values($acount,96,523,'".AddSlashes(pg_result($resaco,$conresaco,'v01_valor'))."','$this->v01_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_obs"]) || $this->v01_obs != "")
           $resac = db_query("insert into db_acount values($acount,96,518,'".AddSlashes(pg_result($resaco,$conresaco,'v01_obs'))."','$this->v01_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_numdig"]) || $this->v01_numdig != "")
           $resac = db_query("insert into db_acount values($acount,96,515,'".AddSlashes(pg_result($resaco,$conresaco,'v01_numdig'))."','$this->v01_numdig',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_instit"]) || $this->v01_instit != "")
           $resac = db_query("insert into db_acount values($acount,96,10572,'".AddSlashes(pg_result($resaco,$conresaco,'v01_instit'))."','$this->v01_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_dtinclusao"]) || $this->v01_dtinclusao != "")
           $resac = db_query("insert into db_acount values($acount,96,15195,'".AddSlashes(pg_result($resaco,$conresaco,'v01_dtinclusao'))."','$this->v01_dtinclusao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_processo"]) || $this->v01_processo != "")
           $resac = db_query("insert into db_acount values($acount,96,18801,'".AddSlashes(pg_result($resaco,$conresaco,'v01_processo'))."','$this->v01_processo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_titular"]) || $this->v01_titular != "")
           $resac = db_query("insert into db_acount values($acount,96,18802,'".AddSlashes(pg_result($resaco,$conresaco,'v01_titular'))."','$this->v01_titular',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v01_dtprocesso"]) || $this->v01_dtprocesso != "")
           $resac = db_query("insert into db_acount values($acount,96,18803,'".AddSlashes(pg_result($resaco,$conresaco,'v01_dtprocesso'))."','$this->v01_dtprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v01_coddiv;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v01_coddiv;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v01_coddiv;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($v01_coddiv=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v01_coddiv));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,508,'$v01_coddiv','E')");
         $resac = db_query("insert into db_acount values($acount,96,508,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_coddiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,509,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,510,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_dtinsc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,511,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_exerc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,512,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,513,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,514,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_numtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,516,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_vlrhis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,517,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_proced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,519,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_livro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,520,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,521,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,522,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_dtoper'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,523,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,518,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,515,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_numdig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,10572,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,15195,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_dtinclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,18801,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,18802,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_titular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,96,18803,'','".AddSlashes(pg_result($resaco,$iresaco,'v01_dtprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from divida
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v01_coddiv != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v01_coddiv = $v01_coddiv ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v01_coddiv;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v01_coddiv;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v01_coddiv;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:divida";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function incluir_adivida (){

    $sql_inclui_adivida = "
                           insert into adivida (
                                                v01_coddiv,
                                                v01_numcgm,
                                                v01_dtinsc,
                                                v01_exerc,
                                                v01_numpre,
                                                v01_numpar,
                                                v01_numtot,
                                                v01_numdig,
                                                v01_vlrhis,
                                                v01_proced,
                                                v01_obs,
                                                v01_livro,
                                                v01_folha,
                                                v01_dtvenc,
                                                v01_dtoper,
                                                v01_valor,
                                                v01_dtanul,
                                                v01_loganul,
                                                v01_motanul
                                               )
                           values              (
                                                $this->v01_coddiv,
                                                $this->v01_numcgm,
                                                '$this->v01_dtinsc',
                                                $this->v01_exerc,
                                                $this->v01_numpre,
                                                $this->v01_numpar,
                                                $this->v01_numtot,
                                                $this->v01_numdig,
                                                $this->v01_vlrhis,
                                                $this->v01_proced,
                                                '".addslashes($this->v01_obs)."',
                                                $this->v01_livro,
                                                $this->v01_folha,
                                                '$this->v01_dtvenc',
                                                '$this->v01_dtoper',
                                                $this->v01_valor,
                                                '".date("Y-m-d",db_getsession("DB_datausu"))."',
                                                ".db_getsession("DB_id_usuario").",
                                                'Exclusão de dívida'
                                               )
                          ";
     $result = @db_query($sql_inclui_adivida);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql = " ($this->v01_coddiv) nao Incluído. Inclusao Abortada.";
         $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg.=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql = " ($this->v01_coddiv) nao Incluído. Inclusao Abortada.";
         $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg.=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao Efetivada com Sucesso\\n";
     $this->erro_sql.= "Valores : ".$this->v01_coddiv;
     $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg.=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     return $result;

  }
   function sql_query ( $v01_coddiv=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from divida ";
     $sql .= "      inner join cgm             on  cgm.z01_numcgm = divida.v01_numcgm";
     $sql .= "      inner join db_config       on  db_config.codigo = divida.v01_instit";
     $sql .= "      inner join proced          on  proced.v03_codigo = divida.v01_proced";
//     $sql .= "      inner join cgm             on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join histcalc        on  histcalc.k01_codigo = proced.k00_hist";
     $sql .= "      inner join tabrec          on  tabrec.k02_codigo = proced.v03_receit";
     $sql .= "      inner join db_config  as a on  a.codigo = proced.v03_instit";
     $sql .= "      left join tipoproced      on  tipoproced.v07_sequencial = proced.v03_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($v01_coddiv!=null ){
         $sql2 .= " where divida.v01_coddiv = $v01_coddiv ";
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
   function sql_query_divida ( $v01_coddiv=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from divida ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = divida.v01_numcgm";
     $sql .= "      inner join proced  on  proced.v03_codigo = divida.v01_proced";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = proced.k00_hist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = proced.v03_receit";
//     $sql .= "      left outer join divmatric on divida.v01_coddiv = divmatric.v01_coddiv";
//     $sql .= "      left outer join arrecant on divida.v01_numpre = arrecant.k00_numpre and divida.v01_numpar = arrecant.k00_numpar";
//     $sql .= "      left outer join arrecad on divida.v01_numpre = arrecad.k00_numpre and divida.v01_numpar = arrecad.k00_numpar";
//     $sql .= "      left outer join termodiv on divida.v01_coddiv = termodiv.coddiv";
     $sql2 = "";
     if($dbwhere==""){
       if($v01_coddiv!=null ){
         $sql2 .= " where divida.v01_coddiv = $v01_coddiv ";
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
   function sql_query_file ( $v01_coddiv=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from divida ";
     $sql2 = "";
     if($dbwhere==""){
       if($v01_coddiv!=null ){
         $sql2 .= " where divida.v01_coddiv = $v01_coddiv ";
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
   function sql_queryinscricao ( $v01_inscr=null,$campos='*',$ordem=null,$where=""){
     $sql ='select ';
     $sWhere = " where ";
     $and = "";
     if($campos != '*' ){
       $campos_sql = split('#',$campos);
       $virgula = '';
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ',';
       }
     }else{
       $sql .= $campos;
     }
     $sql .= ' from divida inner join divinscr on divida.v01_coddiv = divinscr.v01_coddiv ';
     if(isset($v01_inscr) && $v01_inscr!=null ){
       $sql .= " $sWhere divinscr.v01_inscr = $v01_inscr ";
       $and = "and";
       $sWhere = "";
     }
     if($where != ""){
       $sql .= " $sWhere $and $where ";
     }
     if($ordem != null ){
       $sql .= ' order by ';
       $campos_sql = split('#',$ordem);
       $virgula = '';
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ',';
       }
     }
     return $sql;
   }
   function sql_querymatric ( $v01_matric=null,$campos='*',$ordem=null,$where=""){
     $and = "";
     $sWhere = " where " ;
     $sql ='select ';
     if($campos != '*' ){
       $campos_sql = split('#',$campos);
       $virgula = '';
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ',';
       }
     }else{
       $sql .= $campos;
     }
     $sql .= ' from divida inner join divmatric on divida.v01_coddiv = divmatric.v01_coddiv ';
     if(isset($v01_matric) && $v01_matric!=null ){
       $sql .= " $sWhere divmatric.v01_matric = $v01_matric ";
       $sWhere = "" ;
       $and  = "and";
     }
     if($where != ""){
       $sql .= " $sWhere $and $where ";
     }

     if($ordem != null ){
       $sql .= ' order by ';
       $campos_sql = split('#',$ordem);
       $virgula = '';
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ',';
       }
     }
     return $sql;
  }
   function sql_querynumcgm ( $v01_numcgm=null,$campos='*',$ordem=null, $where=""){
     $sql ='select ';
     $sWhere = " where ";
     $and = "";
     if($campos != '*' ){
       $campos_sql = split('#',$campos);
       $virgula = '';
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ',';
       }
     }else{
       $sql .= $campos;
     }
     $sql .= ' from divida inner join cgm on z01_numcgm = v01_numcgm ';
     if(isset($v01_numcgm) && $v01_numcgm!=null ){
       $sql .= " $sWhere v01_numcgm = $v01_numcgm ";
       $sWhere = "";
       $and = "and";
     }
     if($where != ""){
       $sql .= " $sWhere $and $where ";
     }
     if($ordem != null ){
       $sql .= ' order by ';
       $campos_sql = split('#',$ordem);
       $virgula = '';
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ',';
       }
     }
     return $sql;
   }
   function sql_queryproced ( $v01_proced=null,$campos="*",$ordem=null){
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
     $sql .= " from divida inner join proced on v03_codigo = v01_proced ";
     if(isset($v01_proced) && $v01_proced!=null ){
       $sql .= " where v01_proced = $v01_proced";
     }
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

  function sql_querysituacao ($v01_coddiv=null,$where="") {

     $sql  = " select case ";
     $sql .= "          when x.aberto is not null then 'divida nao paga' else 'divida ja paga' ";
     $sql .= "        end ";
     $sql .= "   from ( select arrecad.k00_numpre as aberto, ";
     $sql .= "                 arrepaga.k00_numpre as pago ";
     $sql .= "            from divida";
     $sql .= "                 left outer join arrecad  on arrecad.k00_numpre = v01_numpre ";
     $sql .= "                 left outer join arrepaga on arrepaga.k00_numpre = v01_numpre ";
     $sql .= "            where v01_coddiv = ".$v01_coddiv."";
     if ( $where != "") {
       $sql .= " and  $where ";
     }
     $sql .= " ) as x ";

     return $sql;
  }

  function resumo_importacao($iNumpre, $iCadTipo) {

    /**
     * Cadtipo: 3  - ISSQN Variável
     * Cadtipo: 4  - Contribução de Melhoria
     * Cadtipo: 7  - Diversos
     * Cadtipo: 11 - Auto de Infração
     * Cadtipo: 16 - Parcelamento de Diversos
     * Cadtipo: 17 - Parcelamento de Contribuição de Melhoria
     * Cadtipo: 19 - Vistorias
     */
    $sObs = "";
    switch ($iCadTipo) {
      case 3:
         $sSql = "select case
                           when y60_codlev is null
                            then q05_histor
                           else y60_obs
                         end as observacao
                    from arrecad
                         inner join issvar    on issvar.q05_numpre    = arrecad.k00_numpre
                          left join issvarlev on issvarlev.q18_codigo = issvar.q05_codigo
                          left join levanta   on issvarlev.q18_codlev = levanta.y60_codlev
                   where arrecad.k00_numpre = {$iNumpre}";
      break;
      case 4:
        $sSql = "select min(d40_codigo) as d40_codigo,
                        d01_descr,
                        d01_codedi,
                        d09_contri,
                        j14_nome
                  from arrecad
                       inner join arrematric          on arrematric.k00_numpre          = arrecad.k00_numpre
                       inner join contricalc          on contricalc.d09_numpre          = arrecad.k00_numpre
                       inner join contrib             on contrib.d07_contri             = contricalc.d09_contri
                       inner join contlot             on contlot.d05_contri             = contricalc.d09_contri
                       inner join editalrua           on editalrua.d02_contri           = contlot.d05_contri
                       inner join ruas                on ruas.j14_codigo                = editalrua.d02_codigo
                       inner join edital              on edital.d01_codedi              = editalrua.d02_codedi
                       inner join projmelhoriasmatric on projmelhoriasmatric.d41_matric = arrematric.k00_matric
                       inner join projmelhorias       on projmelhorias.d40_codigo       = projmelhoriasmatric.d41_codigo
                 where arrecad.k00_numpre = {$iNumpre}
                 group by d01_descr, d01_codedi,d09_contri,j14_nome";
      break;
      case 7:
        $sSql = "select dv05_obs
                   from diversos
                  where dv05_numpre = {$iNumpre}";
      break;
      case 11:

        $sSql = "select auto.*
                   from arrecad
                        inner join autonumpre on autonumpre.y17_numpre = arrecad.k00_numpre
                        inner join auto       on auto.y50_codauto      = autonumpre.y17_codauto
                  where arrecad.k00_numpre = {$iNumpre}";
      break;
      case 16:
        $sSql = "select array_to_string(array_accum( distinct dv05_coddiver),',') as coddiver,
                        array_to_string(array_accum( distinct dv05_obs),'\n')     as obs,
                        v07_parcel,
                        array_to_string(array_accum(k00_numpar),',')              as parcelas
                   from arrecad
                        inner join termo      on termo.v07_numpre       = arrecad.k00_numpre
                        inner join termodiver on termodiver.dv10_parcel = termo.v07_parcel
                        inner join diversos   on diversos.dv05_coddiver = termodiver.dv10_coddiver
                  where k00_numpre = {$iNumpre}
                  group by dv05_obs, v07_parcel";
      break;
      case 17:
        $sSql = "select min(d40_codigo) as d40_codigo,
                        d01_descr,
                        d01_codedi,
                        d09_contri,
                        j14_nome
                   from arrecad
                        inner join arrematric          on arrematric.k00_numpre          = arrecad.k00_numpre
                        inner join termo               on termo.v07_numpre               = arrecad.k00_numpre
                        inner join termocontrib        on termocontrib.parcel            = termo.v07_parcel
                        inner join contricalc          on contricalc.d09_sequencial      = termocontrib.contricalc
                        inner join contrib             on contrib.d07_contri             = contricalc.d09_contri
                        inner join contlot             on contlot.d05_contri             = contricalc.d09_contri
                        inner join editalrua           on editalrua.d02_contri           = contlot.d05_contri
                        inner join ruas                on ruas.j14_codigo                = editalrua.d02_codigo
                        inner join edital              on edital.d01_codedi             = editalrua.d02_codedi
                        inner join projmelhoriasmatric on projmelhoriasmatric.d41_matric = arrematric.k00_matric
                        inner join projmelhorias       on projmelhorias.d40_codigo       = projmelhoriasmatric.d41_codigo
                  where arrecad.k00_numpre = {$iNumpre}
                  group by d01_descr, d01_codedi,d09_contri,j14_nome";
      break;
      case 19:
        $sSql = "select y70_obs
                   from arrecad
                        inner join vistorianumpre on vistorianumpre.y69_numpre = arrecad.k00_numpre
                        inner join vistorias      on vistorias.y70_codvist     = vistorianumpre.y69_codvist
                  where arrecad.k00_numpre = {$iNumpre}";
      break;
      default:

        $sSql = "";

      break;
    }

    if (!empty($sSql)) {

      $rsObs = $this->sql_record($sSql);
      if ($this->erro_status == "0"){
        return false;
      }
      $oDadosObs = db_utils::fieldsMemory($rsObs,0);
      switch ($iCadTipo) {
        case 3:
          $sObs = $oDadosObs->observacao;
        break;
        case 4:
        case 17:

          $sObs  = "Lista: {$oDadosObs->d40_codigo} - Edital: {$oDadosObs->d01_codedi} - Contribuição: {$oDadosObs->d09_contri} - ";
          $sObs .= "Rua: {$oDadosObs->j14_nome} - Tipo de Contribuição: {$oDadosObs->d01_descr}";
        break;
        case 7:
          $sObs = $oDadosObs->dv05_obs;
        break;
        case 11:
          $sObs  = 'Auto de Infração ' . $oDadosObs->y50_codauto . (!empty($oDadosObs->y50_obs) ? ' - ' : '') . $oDadosObs->y50_obs;
        break;
        case 16:

          $sObs  = "Diverso(s): {$oDadosObs->coddiver} - Valor Ref. a {$oDadosObs->obs} \n";
          $sObs .= "Parcelamento: {$oDadosObs->v07_parcel} - Parcelas: {$oDadosObs->parcelas}";
        break;
        case 19:
          $sObs = $oDadosObs->y70_obs;
        break;
      }

    }

    return $sObs;
  }

  /**
   * Metodo para retornar exercicio da divida, conforme o cadtipo
   * @param  integer $iNumpre
   * @param  integer $iCadTipo
   * @param  integer $iAnoDefult
   * @return integer $iExercicio
   */
  function getExercicioDivida($iNumpre, $iCadTipo, $iAnoDefult) {

    $sSqlExercicio = '';
    $iAnoDivida    = '';
    switch ($iCadTipo) {

      case 1 :  //iptu           iptunump

        $oDaoIptuNump  = db_utils::getDao("iptunump");
        $sSqlExercicio = $oDaoIptuNump->sql_query_file(null, null, "j20_anousu as exercicio", null, "j20_numpre = {$iNumpre}");
      break;


      case 2 : //iss fixo        isscalc

        require_once("classes/db_isscalc_classe.php");
        $oDaoIssCalc   = db_utils::getDao("isscalc");
        $sSqlExercicio = $oDaoIssCalc->sql_query_file(null, null, null, null, $iNumpre, "q01_anousu as exercicio");
      break;


      case 3 : // iss variavel   issvar

        require_once("classes/db_issvar_classe.php");
        $oDaoIssVar = new cl_issvar();
        $sSqlExercicio = $oDaoIssVar->sql_query_file(null, "q05_ano as exercicio", null, "q05_numpre = {$iNumpre}");
      break;


      case 7 : //diversos        diversos

        require_once("classes/db_diversos_classe.php");
        $oDaoDiversos  = new cl_diversos;
        $sSqlExercicio = $oDaoDiversos->sql_query_file(null, "dv05_exerc as exercicio", null, "dv05_numpre = {$iNumpre}");
      break;


      case 9 : //alvara          isscalc

        require_once("classes/db_isscalc_classe.php");
        $oDaoIssCalc   = db_utils::getDao("isscalc");
        $sSqlExercicio = $oDaoIssCalc->sql_query_file(null, null, null, null, $iNumpre, "q01_anousu as exercicio");
      break;


      case 19 : //vistorias      vistoria

        require_once("classes/db_vistorianumpre_classe.php");
        $oDaoVistoria  = new cl_vistorianumpre;
        $sSqlExercicio = $oDaoVistoria->sql_query(null, "extract (year from y70_data) as exercicio", null, "y69_numpre = {$iNumpre}") ;
      break;

      default :  // quando não for nenhum desses casos
        $iAnoDivida = $iAnoDefult;
      break;

    }
    $iAnoDivida = $iAnoDefult;
    if (!empty($sSqlExercicio)) {

      $rsExercicio = $this->sql_record($sSqlExercicio);
      if ($this->numrows > 0) {

        $iAnoDivida  = db_utils::fieldsMemory($rsExercicio, 0)->exercicio;
      }
    }
    return $iAnoDivida;
  }

  /**
   * metodo para retornar o resumo de receitas das dividas inscritas
   * @param  date $dDataInicial
   * @param  date $dDataFinal
   * @return array $aDados
   */

  function getResumoDeReceitas($dDataInicial = null, $dDataFinal = null) {

   $dtDataUsu = date('Y-m-d',db_getsession('DB_datausu'));
   $iAnoHoje  = date('Y', db_getsession('DB_datausu'));

   $sWhere  = "     divida.v01_instit = " . db_getsession('DB_instit') ."  \n";
   $sWhere .= " and v01_dtinclusao between '" . $dDataInicial . "'  \n";
   $sWhere .= "                        and '" . $dDataFinal . "'    \n";


   $sSql  = " select distinct *,( corrigido + juros + multa) as total, (fim - inicio)  as tempo from (                   \n";
   $sSql .= " select v01_coddiv,                                                                                         \n";
   $sSql .= "        v01_numpre,                                                                                         \n";
   $sSql .= "        v01_numpar,                                                                                         \n";
   $sSql .= "        k00_receit,                                                                                         \n";
   $sSql .= "        v01_proced,                                                                                         \n";
   $sSql .= "        cadtipo.k03_tipo,                                                                                   \n";
   $sSql .= "        cadtipo.k03_descr as descrtipo,                                                                     \n";
   $sSql .= "        tabrec.k02_drecei as descrreceit,                                                                   \n";
   $sSql .= "        tabrec.k02_codigo as codigreceittesouraria,                                                         \n";
   $sSql .= "        taborc.k02_estorc as codigreceitorcamentaria,                                                       \n";
   $sSql .= "        proced.v03_dcomp  as descrproced,                                                                   \n";
   $sSql .= "        v07_descricao     as descrtipoproced,                                                               \n";
   $sSql .= "        v01_numcgm,                                                                                         \n";
   $sSql .= "        v02_usuario,                                                                                        \n";
   $sSql .= "        v02_data,                                                                                           \n";
   $sSql .= "        v02_hora,                                                                                           \n";
   $sSql .= "        v02_datafim,                                                                                        \n";
   $sSql .= "        v02_horafim,                                                                                        \n";
   $sSql .= "        v02_tipo,                                                                                           \n";
   $sSql .= "        v02_instit,                                                                                         \n";
   $sSql .= "        v02_divimporta,                                                                                     \n";
   $sSql .= "        v03_tributaria,                                                                                     \n";
   $sSql .= "        v07_descricao,                                                                                      \n";
   $sSql .= "        v01_dtvenc,                                                                                         \n";
   $sSql .= "        v01_exerc,                                                                                          \n";
   $sSql .= "        v01_vlrhis,                                                                                         \n";
   $sSql .= "        db_usuarios.nome as usuario,                                                                        \n";
   $sSql .= "        case                                                                                                \n";
   $sSql .= "            when arrematric.k00_matric is not null then 'M-'||k00_matric                                    \n";
   $sSql .= "            when arreinscr.k00_inscr   is not null then 'I-'||k00_inscr                                     \n";
   $sSql .= "          else 'C-'||arrenumcgm.k00_numcgm                                                                  \n";
   $sSql .= "        end as origem,                                                                                      \n";
   $sSql .= "        case                                                                                                \n";
   $sSql .= "            when arrematric.k00_matric is not null then                                                     \n";
   $sSql .= "                ( select rvNome from fc_busca_envolvidos(true,1,'M',arrematric.k00_matric) limit 1)         \n";
   $sSql .= "            when arreinscr.k00_inscr is not null then                                                       \n";
   $sSql .= "                ( select rvNome from fc_busca_envolvidos(true,1,'I',arreinscr.k00_inscr) limit 1 )          \n";
   $sSql .= "          else ( select rvNome from fc_busca_envolvidos(true,1,'C',arrenumcgm.k00_numcgm) limit 1 )         \n";
   $sSql .= "        end as nomecontribuinte,                                                                            \n";
   $sSql .= "        case                                                                                                \n";
   $sSql .= "          when v02_divimporta is not null then  fc_corre( arrecad.k00_receit,                               \n";
   $sSql .= "                                                          arrecad.k00_dtvenc,                               \n";
   $sSql .= "                                                          arrecad.k00_valor,                                \n";
   $sSql .= "                                                          v02_datafim,                                      \n";
   $sSql .= "                                                          cast(extract(year from v02_datafim) as integer),  \n";
   $sSql .= "                                                          v02_datafim )                                     \n";
   $sSql .= "          else fc_corre( arrecad.k00_receit,                                                                \n";
   $sSql .= "                         arrecad.k00_dtvenc,                                                                \n";
   $sSql .= "                         arrecad.k00_valor,                                                                 \n";
   $sSql .= "                         '{$dtDataUsu}',                                                                    \n";
   $sSql .= "                         '{$iAnoHoje}',                                                                     \n";
   $sSql .= "                         '{$dtDataUsu}' )                                                                   \n";
   $sSql .= "        end as corrigido,                                                                                   \n";
   $sSql .= "        case                                                                                                \n";
   $sSql .= "          when v02_divimporta is not null then                                                              \n";
   $sSql .= "          round(( arrecad.k00_valor * coalesce( fc_juros( arrecad.k00_receit,                               \n";
   $sSql .= "                                                          arrecad.k00_dtvenc,                               \n";
   $sSql .= "                                                          v02_datafim,                                      \n";
   $sSql .= "                                                          v02_datafim,                                      \n";
   $sSql .= "                                                          false,                                            \n";
   $sSql .= "                                                          cast( extract(                                    \n";
   $sSql .= "                                                          year from v02_datafim) as integer)),0)),2)        \n";
   $sSql .= "          else round(( arrecad.k00_valor * coalesce( fc_juros( arrecad.k00_receit,                          \n";
   $sSql .= "                                                               arrecad.k00_dtvenc,                          \n";
   $sSql .= "                                                               '{$dtDataUsu}',                              \n";
   $sSql .= "                                                               '{$dtDataUsu}',                              \n";
   $sSql .= "                                                               false,                                       \n";
   $sSql .= "                                                               '{$iAnoHoje}'),0)),2)                        \n";
   $sSql .= "        end as juros,                                                                                       \n";
   $sSql .= "        case                                                                                                \n";
   $sSql .= "          when v02_divimporta is not null then                                                              \n";
   $sSql .= "          round(( arrecad.k00_valor * coalesce( fc_multa( arrecad.k00_receit,                               \n";
   $sSql .= "                                                          arrecad.k00_dtvenc,                               \n";
   $sSql .= "                                                          v02_datafim,                                      \n";
   $sSql .= "                                                          arrecad.k00_dtoper,                               \n";
   $sSql .= "                                                          cast( extract(                                    \n";
   $sSql .= "                                                          year from v02_datafim) as integer)),0)),2)        \n";
   $sSql .= "          else round(( arrecad.k00_valor * coalesce( fc_multa( arrecad.k00_receit,                          \n";
   $sSql .= "                                                          arrecad.k00_dtvenc,                               \n";
   $sSql .= "                                                          '{$dtDataUsu}',                                   \n";
   $sSql .= "                                                          arrecad.k00_dtoper,                               \n";
   $sSql .= "                                                          {$iAnoHoje}),0)),2)                               \n";
   $sSql .= "        end as multa,                                                                                       \n";
   $sSql .= "        ( to_timestamp(                                                                                     \n";
   $sSql .= "           (divimporta.v02_data || ' ' ||divimporta.v02_hora)::text, 'YYYY-MM-DD HH24:MI') ) as inicio,     \n";
   $sSql .= "        ( to_timestamp(                                                                                     \n";
   $sSql .= "           (divimporta.v02_datafim || ' ' ||divimporta.v02_horafim)::text, 'YYYY-MM-DD HH24:MI') ) as fim   \n";
   $sSql .= "   from divida                                                                                              \n";
   $sSql .= "        inner join cgm           on cgm.z01_numcgm             = divida.v01_numcgm                          \n";
   $sSql .= "        inner join proced        on proced.v03_codigo          = divida.v01_proced                          \n";
   $sSql .= "        inner join tipoproced    on tipoproced.v07_sequencial  = proced.v03_tributaria                      \n";
   $sSql .= "        inner join arrecad       on arrecad.k00_numpre         = divida.v01_numpre                          \n";
   $sSql .= "                                and arrecad.k00_numpar         = divida.v01_numpar                          \n";
   $sSql .= "        inner join tabrec        on tabrec.k02_codigo          = arrecad.k00_receit                         \n";
   $sSql .= "        inner join taborc        on taborc.k02_codigo          = tabrec.k02_codigo                          \n";
   $sSql .= "                                and taborc.k02_anousu          = {$iAnoHoje}                                \n";
   $sSql .= "        inner join arretipo      on arretipo.k00_tipo          = arrecad.k00_tipo                           \n";
   $sSql .= "        inner join cadtipo       on cadtipo.k03_tipo           = arretipo.k03_tipo                          \n";
   $sSql .= "        left  join divimportareg on divimportareg.v04_coddiv   = divida.v01_coddiv                          \n";
   $sSql .= "        left  join divimporta    on divimporta.v02_divimporta  = divimportareg.v04_divimporta               \n";
   $sSql .= "        left  join db_usuarios   on db_usuarios.id_usuario     = divimporta.v02_usuario                     \n";
   $sSql .= "        left  join arrematric    on arrematric.k00_numpre      = divida.v01_numpre                          \n";
   $sSql .= "        left  join arreinscr     on arreinscr.k00_numpre       = divida.v01_numpre                          \n";
   $sSql .= "        left  join arrenumcgm    on arrenumcgm.k00_numpre      = divida.v01_numpre                          \n";
   $sSql .= "   where {$sWhere}                                                                                          \n";
   $sSql .= "  union all                                                                                                 \n";
   $sSql .= "  select v01_coddiv,                                                                                        \n";
   $sSql .= "         v01_numpre,                                                                                        \n";
   $sSql .= "         v01_numpar,                                                                                        \n";
   $sSql .= "         k00_receit,                                                                                        \n";
   $sSql .= "         v01_proced,                                                                                        \n";
   $sSql .= "         cadtipo.k03_tipo,                                                                                  \n";
   $sSql .= "         cadtipo.k03_descr as descrtipo,                                                                    \n";
   $sSql .= "         tabrec.k02_drecei as descrreceit,                                                                  \n";
   $sSql .= "         tabrec.k02_codigo as codigreceittesouraria,                                                        \n";
   $sSql .= "         taborc.k02_estorc as codigreceitorcamentaria,                                                      \n";
   $sSql .= "         proced.v03_dcomp  as descrproced,                                                                  \n";
   $sSql .= "         v07_descricao     as descrtipoproced,                                                              \n";
   $sSql .= "         v01_numcgm,                                                                                        \n";
   $sSql .= "         v02_usuario,                                                                                       \n";
   $sSql .= "         v02_data,                                                                                          \n";
   $sSql .= "         v02_hora,                                                                                          \n";
   $sSql .= "         v02_datafim,                                                                                       \n";
   $sSql .= "         v02_horafim,                                                                                       \n";
   $sSql .= "         v02_tipo,                                                                                          \n";
   $sSql .= "         v02_instit,                                                                                        \n";
   $sSql .= "         v02_divimporta,                                                                                    \n";
   $sSql .= "         v03_tributaria,                                                                                    \n";
   $sSql .= "         v07_descricao,                                                                                     \n";
   $sSql .= "         v01_dtvenc,                                                                                        \n";
   $sSql .= "         v01_exerc,                                                                                         \n";
   $sSql .= "         v01_vlrhis,                                                                                        \n";
   $sSql .= "         db_usuarios.nome as usuario,                                                                       \n";
   $sSql .= "         case                                                                                               \n";
   $sSql .= "             when arrematric.k00_matric is not null then 'M-'||k00_matric                                   \n";
   $sSql .= "             when arreinscr.k00_inscr is not null then 'I-'||k00_inscr                                      \n";
   $sSql .= "           else 'C-'||arrenumcgm.k00_numcgm                                                                 \n";
   $sSql .= "         end as origem,                                                                                     \n";
   $sSql .= "         case                                                                                               \n";
   $sSql .= "             when arrematric.k00_matric is not null then                                                    \n";
   $sSql .= "                  ( select rvNome from fc_busca_envolvidos(true, 1,'M',arrematric.k00_matric) limit 1 )     \n";
   $sSql .= "             when arreinscr.k00_inscr is not null then                                                      \n";
   $sSql .= "                  ( select rvNome from fc_busca_envolvidos(true,1,'I',arreinscr.k00_inscr) limit 1 )        \n";
   $sSql .= "           else ( select rvNome from fc_busca_envolvidos(true,1,'C',arrenumcgm.k00_numcgm) limit 1 )        \n";
   $sSql .= "         end as nomecontribuinte,                                                                           \n";
   $sSql .= "         case                                                                                               \n";
   $sSql .= "           when v02_divimporta is not null then  fc_corre( arrecant.k00_receit,                             \n";
   $sSql .= "                                                           arrecant.k00_dtvenc,                             \n";
   $sSql .= "                                                           arrecant.k00_valor,                              \n";
   $sSql .= "                                                           v02_datafim,                                     \n";
   $sSql .= "                                                           cast(extract(year from v02_datafim) as integer), \n";
   $sSql .= "                                                           v02_datafim )                                    \n";
   $sSql .= "           else fc_corre( arrecant.k00_receit,                                                              \n";
   $sSql .= "                          arrecant.k00_dtvenc,                                                              \n";
   $sSql .= "                          arrecant.k00_valor,                                                               \n";
   $sSql .= "                          '{$dtDataUsu}',                                                                   \n";
   $sSql .= "                          '{$iAnoHoje}',                                                                    \n";
   $sSql .= "                          '{$dtDataUsu}' )                                                                  \n";
   $sSql .= "         end as corrigido,                                                                                  \n";
   $sSql .= "         case                                                                                               \n";
   $sSql .= "           when v02_divimporta is not null then                                                             \n";
   $sSql .= "           round(( arrecant.k00_valor * coalesce( fc_juros( arrecant.k00_receit,                            \n";
   $sSql .= "                                                          arrecant.k00_dtvenc,                              \n";
   $sSql .= "                                                          v02_datafim,                                      \n";
   $sSql .= "                                                          v02_datafim,                                      \n";
   $sSql .= "                                                          false,                                            \n";
   $sSql .= "                                                          cast( extract(                                    \n";
   $sSql .= "                                                          year from v02_datafim) as integer)),0)),2)        \n";
   $sSql .= "           else round(( arrecant.k00_valor * coalesce( fc_juros( arrecant.k00_receit,                       \n";
   $sSql .= "                                                               arrecant.k00_dtvenc,                         \n";
   $sSql .= "                                                               '{$dtDataUsu}',                              \n";
   $sSql .= "                                                               '{$dtDataUsu}',                              \n";
   $sSql .= "                                                               false,                                       \n";
   $sSql .= "                                                               '{$iAnoHoje}'),0)),2)                        \n";
   $sSql .= "         end as juros,                                                                                      \n";
   $sSql .= "         case                                                                                               \n";
   $sSql .= "           when v02_divimporta is not null then                                                             \n";
   $sSql .= "           round(( arrecant.k00_valor * coalesce( fc_multa( arrecant.k00_receit,                            \n";
   $sSql .= "                                                          arrecant.k00_dtvenc,                              \n";
   $sSql .= "                                                          v02_datafim,                                      \n";
   $sSql .= "                                                          arrecant.k00_dtoper,                              \n";
   $sSql .= "                                                          cast( extract(                                    \n";
   $sSql .= "                                                          year from v02_datafim) as integer)),0)),2)        \n";
   $sSql .= "           else round(( arrecant.k00_valor * coalesce( fc_multa( arrecant.k00_receit,                       \n";
   $sSql .= "                                                          arrecant.k00_dtvenc,                              \n";
   $sSql .= "                                                          '{$dtDataUsu}',                                   \n";
   $sSql .= "                                                          arrecant.k00_dtoper,                              \n";
   $sSql .= "                                                          {$iAnoHoje}),0)),2)                               \n";
   $sSql .= "         end as multa,                                                                                      \n";
   $sSql .= "         ( to_timestamp(                                                                                    \n";
   $sSql .= "            (divimporta.v02_data || ' ' ||divimporta.v02_hora)::text, 'YYYY-MM-DD HH24:MI') ) as inicio,    \n";
   $sSql .= "         ( to_timestamp(                                                                                    \n";
   $sSql .= "            (divimporta.v02_datafim || ' ' ||divimporta.v02_horafim)::text, 'YYYY-MM-DD HH24:MI') ) as fim  \n";
   $sSql .= "    from divida                                                                                             \n";
   $sSql .= "         inner join cgm           on cgm.z01_numcgm             = divida.v01_numcgm                         \n";
   $sSql .= "         inner join proced        on proced.v03_codigo          = divida.v01_proced                         \n";
   $sSql .= "         inner join tipoproced    on tipoproced.v07_sequencial  = proced.v03_tributaria                     \n";
   $sSql .= "         inner join arrecant      on arrecant.k00_numpre        = divida.v01_numpre                         \n";
   $sSql .= "                                 and arrecant.k00_numpar        = divida.v01_numpar                         \n";
   $sSql .= "         inner join tabrec        on tabrec.k02_codigo          = arrecant.k00_receit                       \n";
   $sSql .= "         inner join taborc        on taborc.k02_codigo          = tabrec.k02_codigo                         \n";
   $sSql .= "                                 and taborc.k02_anousu          = {$iAnoHoje}                               \n";
   $sSql .= "         inner join arretipo      on arretipo.k00_tipo          = arrecant.k00_tipo                         \n";
   $sSql .= "         inner join cadtipo       on cadtipo.k03_tipo           = arretipo.k03_tipo                         \n";
   $sSql .= "         left  join divimportareg on divimportareg.v04_coddiv   = divida.v01_coddiv                         \n";
   $sSql .= "         left  join divimporta    on divimporta.v02_divimporta  = divimportareg.v04_divimporta              \n";
   $sSql .= "         left  join db_usuarios   on db_usuarios.id_usuario     = divimporta.v02_usuario                    \n";
   $sSql .= "         left  join arrematric    on arrematric.k00_numpre      = divida.v01_numpre                         \n";
   $sSql .= "         left  join arreinscr     on arreinscr.k00_numpre       = divida.v01_numpre                         \n";
   $sSql .= "         left  join arrenumcgm    on arrenumcgm.k00_numpre      = divida.v01_numpre                         \n";
   $sSql .= "   where {$sWhere}                                                                                          \n";
   $sSql .= "  union all                                                                                                 \n";
   $sSql .= "  select v01_coddiv,                                                                                        \n";
   $sSql .= "         v01_numpre,                                                                                        \n";
   $sSql .= "         v01_numpar,                                                                                        \n";
   $sSql .= "         k00_receit,                                                                                        \n";
   $sSql .= "         v01_proced,                                                                                        \n";
   $sSql .= "         cadtipo.k03_tipo,                                                                                  \n";
   $sSql .= "         cadtipo.k03_descr as descrtipo,                                                                    \n";
   $sSql .= "         tabrec.k02_drecei as descrreceit,                                                                  \n";
   $sSql .= "         tabrec.k02_codigo as codigreceittesouraria,                                                        \n";
   $sSql .= "         taborc.k02_estorc as codigreceitorcamentaria,                                                      \n";
   $sSql .= "         proced.v03_dcomp  as descrproced,                                                                  \n";
   $sSql .= "         v07_descricao     as descrtipoproced,                                                              \n";
   $sSql .= "         v01_numcgm,                                                                                        \n";
   $sSql .= "         v02_usuario,                                                                                       \n";
   $sSql .= "         v02_data,                                                                                          \n";
   $sSql .= "         v02_hora,                                                                                          \n";
   $sSql .= "         v02_datafim,                                                                                       \n";
   $sSql .= "         v02_horafim,                                                                                       \n";
   $sSql .= "         v02_tipo,                                                                                          \n";
   $sSql .= "         v02_instit,                                                                                        \n";
   $sSql .= "         v02_divimporta,                                                                                    \n";
   $sSql .= "         v03_tributaria,                                                                                    \n";
   $sSql .= "         v07_descricao,                                                                                     \n";
   $sSql .= "         v01_dtvenc,                                                                                        \n";
   $sSql .= "         v01_exerc,                                                                                         \n";
   $sSql .= "         v01_vlrhis,                                                                                        \n";
   $sSql .= "         db_usuarios.nome as usuario,                                                                       \n";
   $sSql .= "         case                                                                                               \n";
   $sSql .= "             when arrematric.k00_matric is not null then 'M-'||k00_matric                                   \n";
   $sSql .= "             when arreinscr.k00_inscr is not null then 'I-'||k00_inscr                                      \n";
   $sSql .= "           else 'C-'||arrenumcgm.k00_numcgm                                                                 \n";
   $sSql .= "         end as origem,                                                                                     \n";
   $sSql .= "         case                                                                                               \n";
   $sSql .= "             when arrematric.k00_matric is not null then                                                    \n";
   $sSql .= "                  ( select rvNome from fc_busca_envolvidos(true, 1,'M',arrematric.k00_matric) limit 1 )     \n";
   $sSql .= "             when arreinscr.k00_inscr is not null then                                                      \n";
   $sSql .= "                  ( select rvNome from fc_busca_envolvidos(true,1,'I',arreinscr.k00_inscr) limit 1 )        \n";
   $sSql .= "           else ( select rvNome from fc_busca_envolvidos(true,1,'C',arrenumcgm.k00_numcgm) limit 1 )        \n";
   $sSql .= "         end as nomecontribuinte,                                                                           \n";
   $sSql .= "         case                                                                                               \n";
   $sSql .= "           when v02_divimporta is not null then  fc_corre( arreold.k00_receit,                              \n";
   $sSql .= "                                                           arreold.k00_dtvenc,                              \n";
   $sSql .= "                                                           arreold.k00_valor,                               \n";
   $sSql .= "                                                           v02_datafim,                                     \n";
   $sSql .= "                                                           cast(extract(year from v02_datafim) as integer), \n";
   $sSql .= "                                                           v02_datafim )                                    \n";
   $sSql .= "           else fc_corre( arreold.k00_receit,                                                               \n";
   $sSql .= "                          arreold.k00_dtvenc,                                                               \n";
   $sSql .= "                          arreold.k00_valor,                                                                \n";
   $sSql .= "                          '{$dtDataUsu}',                                                                   \n";
   $sSql .= "                          '{$iAnoHoje}',                                                                    \n";
   $sSql .= "                          '{$dtDataUsu}' )                                                                  \n";
   $sSql .= "         end as corrigido,                                                                                  \n";
   $sSql .= "         case                                                                                               \n";
   $sSql .= "           when v02_divimporta is not null then                                                             \n";
   $sSql .= "           round(( arreold.k00_valor * coalesce( fc_juros( arreold.k00_receit,                              \n";
   $sSql .= "                                                          arreold.k00_dtvenc,                               \n";
   $sSql .= "                                                          v02_datafim,                                      \n";
   $sSql .= "                                                          v02_datafim,                                      \n";
   $sSql .= "                                                          false,                                            \n";
   $sSql .= "                                                          cast( extract(                                    \n";
   $sSql .= "                                                          year from v02_datafim) as integer)),0)),2)        \n";
   $sSql .= "           else round(( arreold.k00_valor * coalesce( fc_juros( arreold.k00_receit,                         \n";
   $sSql .= "                                                               arreold.k00_dtvenc,                          \n";
   $sSql .= "                                                               '{$dtDataUsu}',                              \n";
   $sSql .= "                                                               '{$dtDataUsu}',                              \n";
   $sSql .= "                                                               false,                                       \n";
   $sSql .= "                                                               '{$iAnoHoje}'),0)),2)                        \n";
   $sSql .= "         end as juros,                                                                                      \n";
   $sSql .= "         case                                                                                               \n";
   $sSql .= "           when v02_divimporta is not null then                                                             \n";
   $sSql .= "           round(( arreold.k00_valor * coalesce( fc_multa( arreold.k00_receit,                              \n";
   $sSql .= "                                                          arreold.k00_dtvenc,                               \n";
   $sSql .= "                                                          v02_datafim,                                      \n";
   $sSql .= "                                                          arreold.k00_dtoper,                               \n";
   $sSql .= "                                                          cast( extract(                                    \n";
   $sSql .= "                                                          year from v02_datafim) as integer)),0)),2)        \n";
   $sSql .= "           else round(( arreold.k00_valor * coalesce( fc_multa( arreold.k00_receit,                         \n";
   $sSql .= "                                                          arreold.k00_dtvenc,                               \n";
   $sSql .= "                                                          '{$dtDataUsu}',                                   \n";
   $sSql .= "                                                          arreold.k00_dtoper,                               \n";
   $sSql .= "                                                          {$iAnoHoje}),0)),2)                               \n";
   $sSql .= "         end as multa,                                                                                      \n";
   $sSql .= "         ( to_timestamp(                                                                                    \n";
   $sSql .= "            (divimporta.v02_data || ' ' ||divimporta.v02_hora)::text, 'YYYY-MM-DD HH24:MI') ) as inicio,    \n";
   $sSql .= "         ( to_timestamp(                                                                                    \n";
   $sSql .= "            (divimporta.v02_datafim || ' ' ||divimporta.v02_horafim)::text, 'YYYY-MM-DD HH24:MI') ) as fim  \n";
   $sSql .= "    from divida                                                                                             \n";
   $sSql .= "         inner join cgm           on cgm.z01_numcgm             = divida.v01_numcgm                         \n";
   $sSql .= "         inner join proced        on proced.v03_codigo          = divida.v01_proced                         \n";
   $sSql .= "         inner join tipoproced    on tipoproced.v07_sequencial  = proced.v03_tributaria                     \n";
   $sSql .= "         inner join arreold       on arreold.k00_numpre         = divida.v01_numpre                         \n";
   $sSql .= "                                 and arreold.k00_numpar         = divida.v01_numpar                         \n";
   $sSql .= "         inner join tabrec        on tabrec.k02_codigo          = arreold.k00_receit                        \n";
   $sSql .= "         inner join taborc        on taborc.k02_codigo          = tabrec.k02_codigo                         \n";
   $sSql .= "                                 and taborc.k02_anousu          = {$iAnoHoje}                               \n";
   $sSql .= "         inner join arretipo      on arretipo.k00_tipo          = arreold.k00_tipo                          \n";
   $sSql .= "         inner join cadtipo       on cadtipo.k03_tipo           = arretipo.k03_tipo                         \n";
   $sSql .= "         left  join divimportareg on divimportareg.v04_coddiv   = divida.v01_coddiv                         \n";
   $sSql .= "         left  join divimporta    on divimporta.v02_divimporta  = divimportareg.v04_divimporta              \n";
   $sSql .= "         left  join db_usuarios   on db_usuarios.id_usuario     = divimporta.v02_usuario                    \n";
   $sSql .= "         left  join arrematric    on arrematric.k00_numpre      = divida.v01_numpre                         \n";
   $sSql .= "         left  join arreinscr     on arreinscr.k00_numpre       = divida.v01_numpre                         \n";
   $sSql .= "         left  join arrenumcgm    on arrenumcgm.k00_numpre      = divida.v01_numpre                         \n";
   $sSql .= "   where {$sWhere}                                                                                          \n";
   $sSql .= "   ) as x                                                                                                   \n";

   $rsSql        = db_query($sSql);
   $iNumRownsSql = pg_num_rows($rsSql);

   $aLongoPrazo   = array();
   $aCurtoPrazo   = array();

   $aAgrupador['receita']     = 'k00_receit';

   for ( $iInd=0; $iInd < $iNumRownsSql; $iInd++ ) {

    $oDadosImpDivida = db_utils::fieldsMemory($rsSql,$iInd);

    $dtDataLimite = ($oDadosImpDivida->v01_exerc + 1)."-12-31";

    foreach ( $aAgrupador as $sDescrAgrupa => $sCampo ) {

     if (  in_array($oDadosImpDivida->k03_tipo,array(5,15,18)) || ( in_array($oDadosImpDivida->k03_tipo,array(6,13)) && $oDadosImpDivida->v01_dtvenc > $dtDataLimite ) ) {

      if ( isset($aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]) ) {

       $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrHist']   += $oDadosImpDivida->v01_vlrhis;
       $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrCorr']   += $oDadosImpDivida->corrigido;
       $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nMulta']     += $oDadosImpDivida->multa;
       $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nJuros']     += $oDadosImpDivida->juros;
       $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nTotal']     += $oDadosImpDivida->total;
      } else {

       $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['codigreceittesouraria'] = $oDadosImpDivida->codigreceittesouraria;
       $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['codigreceitorcamentaria'] = $oDadosImpDivida->codigreceitorcamentaria;
       $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['descrreceit'] = $oDadosImpDivida->descrreceit;
       $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrHist']    = $oDadosImpDivida->v01_vlrhis;
       $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrCorr']    = $oDadosImpDivida->corrigido;
       $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nMulta']      = $oDadosImpDivida->multa;
       $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nJuros']      = $oDadosImpDivida->juros;
       $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nTotal']      = $oDadosImpDivida->total;
      }

     } else if ( in_array($oDadosImpDivida->k03_tipo,array(6,13)) && $oDadosImpDivida->v01_dtvenc <= $dtDataLimite ) {

      if ( isset($aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]) ) {

       $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrHist']   += $oDadosImpDivida->v01_vlrhis;
       $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrCorr']   += $oDadosImpDivida->corrigido;
       $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nMulta']     += $oDadosImpDivida->multa;
       $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nJuros']     += $oDadosImpDivida->juros;
       $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nTotal']     += $oDadosImpDivida->total;
      } else {

       $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['codigreceittesouraria'] = $oDadosImpDivida->codigreceittesouraria;
       $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['codigreceitorcamentaria'] = $oDadosImpDivida->codigreceitorcamentaria;
       $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['descrreceit'] = $oDadosImpDivida->descrreceit;
       $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrHist']    = $oDadosImpDivida->v01_vlrhis;
       $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrCorr']    = $oDadosImpDivida->corrigido;
       $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nMulta']      = $oDadosImpDivida->multa;
       $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nJuros']      = $oDadosImpDivida->juros;
       $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nTotal']      = $oDadosImpDivida->total;
      }
     }

    }
   }

   // Cria lista com exercícios de 3 anos anteriores aos exercícios selecionados
   $aDataDebitos = explode("-",$dDataInicial);

   for ( $iInd = 1; $iInd <= 3; $iInd++ ) {

    $aExercicioPago[] = ($aDataDebitos[0] - $iInd);
   }

   $aExercicioPago = array_unique($aExercicioPago);

   // Consulta os débitos pagos de 3 anos anteriores aos exercícios selecionados
   $sSqlDebitosPago  = " select arretipo.k03_tipo,                                                ";
   $sSqlDebitosPago .= "        arrecant.k00_receit,                                              ";
   $sSqlDebitosPago .= "        divida.v01_proced,                                                ";
   $sSqlDebitosPago .= "        divida.v01_exerc,                                                 ";
   $sSqlDebitosPago .= "        v03_tributaria,                                                   ";
   $sSqlDebitosPago .= "        round(sum(arrepaga.k00_valor),2) as total                         ";
   $sSqlDebitosPago .= "   from divida                                                            ";
   $sSqlDebitosPago .= "        inner join arrepaga   on arrepaga.k00_numpre = divida.v01_numpre  ";
   $sSqlDebitosPago .= "                             and arrepaga.k00_numpar = divida.v01_numpar  ";
   $sSqlDebitosPago .= "        inner join arrecant   on arrecant.k00_numpre = divida.v01_numpre  ";
   $sSqlDebitosPago .= "                             and arrecant.k00_numpar = divida.v01_numpar  ";
   $sSqlDebitosPago .= "        inner join arretipo   on arretipo.k00_tipo   = arrecant.k00_tipo  ";
   $sSqlDebitosPago .= "        inner join proced     on proced.v03_codigo   = divida.v01_proced              ";
   $sSqlDebitosPago .= "  where extract( year from arrepaga.k00_dtpaga) in (".implode(',',$aExercicioPago).") ";
   $sSqlDebitosPago .= "  group by arretipo.k03_tipo,                                              ";
   $sSqlDebitosPago .= "           arrecant.k00_receit,                                            ";
   $sSqlDebitosPago .= "           divida.v01_proced,                                              ";
   $sSqlDebitosPago .= "           divida.v01_exerc,                                               ";
   $sSqlDebitosPago .= "           v03_tributaria                                                  ";
   $sSqlDebitosPago .= "  order by arretipo.k03_tipo,                                              ";
   $sSqlDebitosPago .= "           divida.v01_exerc,                                               ";
   $sSqlDebitosPago .= "           divida.v01_proced;                                              ";

   $rsDebitosPagos   = db_query($sSqlDebitosPago);
   $iNroDebitosPagos = pg_num_rows($rsDebitosPagos);

   for ( $iInd=0; $iInd < $iNroDebitosPagos; $iInd++ ) {

    $oDebitosPagos = db_utils::fieldsMemory($rsDebitosPagos,$iInd);

    foreach ($aAgrupador as $sDescrAgrupa => $sCampo ) {

     if ( isset($aDebitosPagos[$sDescrAgrupa][$oDebitosPagos->$sCampo]) ) {

      $aDebitosPagos[$sDescrAgrupa][$oDebitosPagos->$sCampo]['nTotal'] += $oDebitosPagos->total;
     } else {

      $aDebitosPagos[$sDescrAgrupa][$oDebitosPagos->$sCampo]['nTotal']  = $oDebitosPagos->total;
     }
    }
   }

   foreach ( $aLongoPrazo as $sTipoAgrupa => $aDadosLongoPrazo ) {

    foreach ( $aDadosLongoPrazo as $sCampoAgrupa =>$aValoresLongoPrazo) {

     if ( isset($aDebitosPagos[$sTipoAgrupa][$sCampoAgrupa])) {

      $nTotalPago   = $aDebitosPagos[$sTipoAgrupa][$sCampoAgrupa]['nTotal'];
      $nTotalPago   = round(( ($nTotalPago/3) * 2 ),2);
      $nTotalProced = $aValoresLongoPrazo['nTotal'];

      // Percentual que será subtraído do logon prazo e incluído no longo prazo
      $nPercentual  = round(( ($nTotalPago*100) / $nTotalProced ),2);

      $nValorHist  = ( ($aValoresLongoPrazo['nVlrHist']/100) * $nPercentual );
      $nValorCorr  = ( ($aValoresLongoPrazo['nVlrCorr']/100) * $nPercentual );
      $nValorMulta = ( ($aValoresLongoPrazo['nMulta']/100) * $nPercentual );
      $nValorJuros = ( ($aValoresLongoPrazo['nJuros']/100) * $nPercentual );
      $nValorTotal = ( ($aValoresLongoPrazo['nTotal']/100) * $nPercentual );

      if ( $nValorTotal < $aValoresLongoPrazo['nTotal'] ) {

       $aLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist'] -= $nValorHist;
       $aLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr'] -= $nValorCorr;
       $aLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']   -= $nValorMulta;
       $aLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']   -= $nValorJuros;
       $aLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']   -= $nValorTotal;

       if ( isset($aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]) ) {

        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']    += $nValorHist;
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']    += $nValorCorr;
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']      += $nValorMulta;
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']      += $nValorJuros;
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']      += $nValorTotal;
       } else {

        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['codigreceittesouraria'] = $aValoresLongoPrazo['codigreceittesouraria'];
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['codigreceitorcamentaria'] = $aValoresLongoPrazo['codigreceitorcamentaria'];
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['descrreceit'] = $aValoresLongoPrazo['descrreceit'];
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']    = $nValorHist;
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']    = $nValorCorr;
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']      = $nValorMulta;
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']      = $nValorJuros;
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']      = $nValorTotal;
       }

      } else {

       if ( isset($aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]) ) {

        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']    += $aValoresLongoPrazo['nVlrHist'];
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']    += $aValoresLongoPrazo['nVlrCorr'];
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']      += $aValoresLongoPrazo['nMulta'];
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']      += $aValoresLongoPrazo['nJuros'];
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']      += $aValoresLongoPrazo['nTotal'];
       } else {

        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['codigreceittesouraria'] = $aValoresLongoPrazo['codigreceittesouraria'];
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['codigreceitorcamentaria'] = $aValoresLongoPrazo['codigreceitorcamentaria'];
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['descrreceit'] = $aValoresLongoPrazo['descrreceit'];
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']    = $aValoresLongoPrazo['nVlrHist'];
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']    = $aValoresLongoPrazo['nVlrCorr'];
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']      = $aValoresLongoPrazo['nMulta'];
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']      = $aValoresLongoPrazo['nJuros'];
        $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']      = $aValoresLongoPrazo['nTotal'];
       }

       unset($aLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]);
      }
     }
    }
   }

   // Remove Tipo de Débito sem valor
   foreach ( $aLongoPrazo as $sTipoAgrupa => $aDadosLongoPrazo ) {

    if ( count($aDadosLongoPrazo) == 0 ) {

     unset($aLongoPrazo[$sTipoAgrupa]);
    }
   }

   $aDados = array();
   $aDados['aLongoPrazo'] = $aLongoPrazo;
   $aDados['aCurtoPrazo'] = $aCurtoPrazo;

   return $aDados;
  }

  public function sql_queryProcessamentoResumoGeralDivida($dDataDebitos,  $sListaExercicios = '') {

    $iInstituicao                 = db_getsession('DB_instit');

    $sSqlParcelamentosCorrigidos  = " create temp table w_parcelamentos_corrigidos as                                              ";
    $sSqlParcelamentosCorrigidos .= " select v07_parcel,                                                                           ";
    $sSqlParcelamentosCorrigidos .= "        v07_numpre,                                                                           ";
    $sSqlParcelamentosCorrigidos .= "        k03_descr,                                                                            ";
    $sSqlParcelamentosCorrigidos .= "        k03_tipo,                                                                             ";
    $sSqlParcelamentosCorrigidos .= "        k22_receit,                                                                           ";
    $sSqlParcelamentosCorrigidos .= "        k02_descr,                                                                            ";
    $sSqlParcelamentosCorrigidos .= "        sum(k22_vlrhis ) as k22_vlrhis,                                                       ";
    $sSqlParcelamentosCorrigidos .= "        sum(k22_vlrcor ) as k22_vlrcor,                                                       ";
    $sSqlParcelamentosCorrigidos .= "        sum(k22_juros  ) as k22_juros,                                                        ";
    $sSqlParcelamentosCorrigidos .= "        sum(k22_multa  ) as k22_multa,                                                        ";
    $sSqlParcelamentosCorrigidos .= "        sum(valor_total) as valor_total                                                       ";
    $sSqlParcelamentosCorrigidos .= "   from (  select v07_parcel, v07_numpre,                                                     ";
    $sSqlParcelamentosCorrigidos .= "                  cadtipo.k03_descr as k03_descr,                                             ";
    $sSqlParcelamentosCorrigidos .= "                  cadtipo.k03_tipo  as k03_tipo,                                              ";
    $sSqlParcelamentosCorrigidos .= "                  k22_receit,                                                                 ";
    $sSqlParcelamentosCorrigidos .= "                  k02_descr,                                                                  ";
    $sSqlParcelamentosCorrigidos .= "                  k22_vlrhis as k22_vlrhis,                                                   ";
    $sSqlParcelamentosCorrigidos .= "                  k22_vlrcor as k22_vlrcor,                                                   ";
    $sSqlParcelamentosCorrigidos .= "                  k22_juros  as k22_juros,                                                    ";
    $sSqlParcelamentosCorrigidos .= "                  k22_multa  as k22_multa,                                                    ";
    $sSqlParcelamentosCorrigidos .= "                  ( k22_vlrcor+k22_juros+k22_multa ) as valor_total                           ";
    $sSqlParcelamentosCorrigidos .= "             from termo                                                                       ";
    $sSqlParcelamentosCorrigidos .= "                  inner join debitos   on debitos.k22_data   = '{$dDataDebitos}'              ";
    $sSqlParcelamentosCorrigidos .= "                                      and debitos.k22_numpre = termo.v07_numpre               ";
    $sSqlParcelamentosCorrigidos .= "                                      and debitos.k22_instit = {$iInstituicao}                ";
    $sSqlParcelamentosCorrigidos .= "                  inner join tabrec    on tabrec.k02_codigo  = debitos.k22_receit             ";
    $sSqlParcelamentosCorrigidos .= "                  inner join arretipo  on arretipo.k00_tipo  = debitos.k22_tipo               ";
    $sSqlParcelamentosCorrigidos .= "                  inner join cadtipo   on cadtipo.k03_tipo   = arretipo.k03_tipo              ";
    $sSqlParcelamentosCorrigidos .= "            where v07_instit = {$iInstituicao}                                                ";
    $sSqlParcelamentosCorrigidos .= "              and k22_data   = '{$dDataDebitos}'                                              ";
    $sSqlParcelamentosCorrigidos .= "        ) as parcelamentos                                                                    ";
    $sSqlParcelamentosCorrigidos .= "  group by v07_parcel,                                                                        ";
    $sSqlParcelamentosCorrigidos .= "           v07_numpre,                                                                        ";
    $sSqlParcelamentosCorrigidos .= "           k03_descr,                                                                         ";
    $sSqlParcelamentosCorrigidos .= "           k03_tipo,                                                                          ";
    $sSqlParcelamentosCorrigidos .= "           k22_receit,                                                                        ";
    $sSqlParcelamentosCorrigidos .= "           k02_descr;                                                                         ";
    $sSqlParcelamentosCorrigidos .= "                                                                                              ";

    $sSqlParcelamentosCorrigidos .= " create index w_parcelamentos_corrigidos_1_in on w_parcelamentos_corrigidos(v07_parcel);      ";

    $rsCriaTabelaTemporaria = db_query($sSqlParcelamentosCorrigidos);

    // DIVIDAS DA CERTIDAO

    // certidoes de parcelamento de divida
    $sSqlCertidaoDividas  = "create temp table w_certidao_dividas as                                                               ";
    $sSqlCertidaoDividas .= "  select 1 as tipo, 'CERTIDÃO DE PARCELAMENTO DE DIVIDA' as tipo_certidao,                            ";
    $sSqlCertidaoDividas .= "         certter.v14_certid as certidao,                                                              ";
    $sSqlCertidaoDividas .= "         certter.v14_parcel as parcel,                                                                ";
    $sSqlCertidaoDividas .= "         certter.v14_parcel as parcelori,                                                             ";
    $sSqlCertidaoDividas .= "         0 as inicial,                                                                                ";
    $sSqlCertidaoDividas .= "         divida.*                                                                                     ";
    $sSqlCertidaoDividas .= "    from certter                                                                                      ";
    $sSqlCertidaoDividas .= "         inner join w_parcelamentos_corrigidos as debitos on debitos.v07_parcel = certter.v14_parcel  ";
    $sSqlCertidaoDividas .= "         inner join termodiv      on termodiv.parcel          = certter.v14_parcel                    ";
    $sSqlCertidaoDividas .= "         inner join divida        on divida.v01_coddiv        = termodiv.coddiv                       ";
    $sSqlCertidaoDividas .= "                                 and divida.v01_instit        = {$iInstituicao}                       ";

    if ($sListaExercicios != '') {
      $sSqlCertidaoDividas .= "                                 and divida.v01_exerc in ( {$sListaExercicios} )                      ";
    }

    $sSqlCertidaoDividas .= "         left  join inicialcert   on inicialcert.v51_certidao = certter.v14_certid                    ";
    $sSqlCertidaoDividas .= "    where certter.v14_certid is null                                                                  ";
    $sSqlCertidaoDividas .= " union all                                                                                            ";
    // certidoes de parcelamento de inicial                                                                                        ";
    $sSqlCertidaoDividas .= "  select 2 as tipo ,'PARCELAMENTO DE INICIAL DE CERTIDAO DO PARCELAMENTO' as tipo_certidao,           ";
    $sSqlCertidaoDividas .= "         certter.v14_certid as certidao,                                                              ";
    $sSqlCertidaoDividas .= "         debitos.v07_parcel as parcel,                                                                ";
    $sSqlCertidaoDividas .= "         certter.v14_parcel as parcelori,                                                             ";
    $sSqlCertidaoDividas .= "         inicialcert.v51_inicial as inicial,                                                          ";
    $sSqlCertidaoDividas .= "         divida.*                                                                                     ";
    $sSqlCertidaoDividas .= "    from termoini                                                                                     ";
    $sSqlCertidaoDividas .= "         inner join w_parcelamentos_corrigidos as debitos on debitos.v07_parcel = termoini.parcel     ";
    $sSqlCertidaoDividas .= "         inner join inicialcert   on inicialcert.v51_inicial = termoini.inicial                       ";
    $sSqlCertidaoDividas .= "         inner join certter       on certter.v14_certid      = inicialcert.v51_certidao               ";
    $sSqlCertidaoDividas .= "         inner join termodiv      on termodiv.parcel         = certter.v14_parcel                     ";
    $sSqlCertidaoDividas .= "         inner join divida        on divida.v01_coddiv       = termodiv.coddiv                        ";
    $sSqlCertidaoDividas .= "                                 and divida.v01_instit       = {$iInstituicao}                        ";

    if ($sListaExercicios != '') {
      $sSqlCertidaoDividas .= "                                 and divida.v01_exerc in ( {$sListaExercicios} )                      ";
    }

    $sSqlCertidaoDividas .= " union all                                                                                            ";
    $sSqlCertidaoDividas .= "  select 3 as tipo, 'PARCELAMENTO DE INICIAL DE CERTIDAO DE DIVIDA' as tipo_certidao,                 ";
    $sSqlCertidaoDividas .= "         certdiv.v14_certid as certidao,                                                              ";
    $sSqlCertidaoDividas .= "         termoini.parcel as parcel,                                                                   ";
    $sSqlCertidaoDividas .= "         termoini.parcel as parcelori,                                                                ";
    $sSqlCertidaoDividas .= "         inicialcert.v51_inicial as inicial,                                                          ";
    $sSqlCertidaoDividas .= "         divida.*                                                                                     ";
    $sSqlCertidaoDividas .= "    from termoini                                                                                     ";
    $sSqlCertidaoDividas .= "         inner join w_parcelamentos_corrigidos as debitos on debitos.v07_parcel = termoini.parcel     ";
    $sSqlCertidaoDividas .= "         inner join inicialcert   on inicialcert.v51_inicial = termoini.inicial                       ";
    $sSqlCertidaoDividas .= "         inner join certdiv       on certdiv.v14_certid      = inicialcert.v51_certidao               ";
    $sSqlCertidaoDividas .= "         inner join divida        on divida.v01_coddiv       = certdiv.v14_coddiv                     ";
    $sSqlCertidaoDividas .= "                                 and divida.v01_instit       = {$iInstituicao}                        ";

    if ($sListaExercicios != '') {
      $sSqlCertidaoDividas .= "                                 and divida.v01_exerc in ( {$sListaExercicios} )                      ";
    }

    $sSqlCertidaoDividas .= " union all                                                                                            ";
    $sSqlCertidaoDividas .= "  select 4 as tipo, 'CERTIDÃO DO FORO' as tipo_certidao,                                              ";
    $sSqlCertidaoDividas .= "         certdiv.v14_certid as certidao,                                                              ";
    $sSqlCertidaoDividas .= "         0 as parcel,                                                                                 ";
    $sSqlCertidaoDividas .= "         0 as parcelori,                                                                              ";
    $sSqlCertidaoDividas .= "         inicialcert.v51_inicial as inicial,                                                          ";
    $sSqlCertidaoDividas .= "         divida.*                                                                                     ";
    $sSqlCertidaoDividas .= "    from inicialcert                                                                                  ";
    $sSqlCertidaoDividas .= "         left  join termoini      on termoini.inicial        = inicialcert.v51_inicial                ";
    $sSqlCertidaoDividas .= "         inner join certdiv       on certdiv.v14_certid      = inicialcert.v51_certidao               ";
    $sSqlCertidaoDividas .= "         inner join divida        on divida.v01_coddiv       = certdiv.v14_coddiv                     ";
    $sSqlCertidaoDividas .= "                                 and divida.v01_instit       = {$iInstituicao}                        ";

    if ($sListaExercicios != '') {
      $sSqlCertidaoDividas .= "                                 and divida.v01_exerc in ( {$sListaExercicios} )                      ";
    }

    $sSqlCertidaoDividas .= "    where termoini.inicial is null                                                                    ";
    $sSqlCertidaoDividas .= " union all                                                                                            ";
    // certidoes de divida normal
    $sSqlCertidaoDividas .= "  select 5 as tipo, 'CERTIDÃO DE DIVIDA' as tipo_certidao,                                            ";
    $sSqlCertidaoDividas .= "         certid.v13_certid as certidao,                                                               ";
    $sSqlCertidaoDividas .= "         0 as parcel,                                                                                 ";
    $sSqlCertidaoDividas .= "         0 as parcelori,                                                                              ";
    $sSqlCertidaoDividas .= "         0 as inicial,                                                                                ";
    $sSqlCertidaoDividas .= "         divida.*                                                                                     ";
    $sSqlCertidaoDividas .= "    from certid                                                                                       ";
    $sSqlCertidaoDividas .= "         inner join certdiv       on certdiv.v14_certid      = certid.v13_certid                      ";
    $sSqlCertidaoDividas .= "         inner join divida        on divida.v01_coddiv       = certdiv.v14_coddiv                     ";
    $sSqlCertidaoDividas .= "                                 and divida.v01_instit       = {$iInstituicao}                        ";

    if ($sListaExercicios != '') {
      $sSqlCertidaoDividas .= "                                 and divida.v01_exerc in ( {$sListaExercicios} )                      ";
    }

    $sSqlCertidaoDividas .= "         left  join inicialcert   on certdiv.v14_certid      = inicialcert.v51_certidao               ";
    $sSqlCertidaoDividas .= "   where inicialcert.v51_certidao is null and certid.v13_instit = {$iInstituicao};                    ";
    $sSqlCertidaoDividas .= " create index w_certidao_dividas_1_in on w_certidao_dividas(inicial);                                 ";
    $sSqlCertidaoDividas .= " create index w_certidao_dividas_2_in on w_certidao_dividas(v01_numpre, v01_numpar);                  ";

    $rsCriaTabelaTemporaria = db_query($sSqlCertidaoDividas);

    // 1º Dívida Ativa
    $sSqlResumoGeral  = " select divida.v01_exerc,                                                                                 ";
    $sSqlResumoGeral .= "				 divida.v01_proced,                                                                                ";
    $sSqlResumoGeral .= "        cadtipo.k03_tipo,                                                                                 ";
    $sSqlResumoGeral .= "        proced.v03_tributaria,                                                                            ";
    $sSqlResumoGeral .= "        k22_receit                       as receit,                                                       ";
    $sSqlResumoGeral .= "        divida.v01_dtvenc                as dtvenc,                                                       ";
    $sSqlResumoGeral .= "        cadtipo.k03_descr                as descrtipo,                                                    ";
    $sSqlResumoGeral .= "				 proced.v03_descr                 as descrproced,                                                  ";
    $sSqlResumoGeral .= "        k02_descr                        as descrreceit,                                                  ";
    $sSqlResumoGeral .= "        tipoproced.v07_descricao         as descrtipoproced,                                              ";
    $sSqlResumoGeral .= " 			 sum(debitos.k22_vlrhis) as vlrhis,                                                                ";
    $sSqlResumoGeral .= " 			 sum(debitos.k22_vlrcor) as vlrcor,                                                                ";
    $sSqlResumoGeral .= " 			 sum(debitos.k22_juros)  as juros,                                                                 ";
    $sSqlResumoGeral .= " 			 sum(debitos.k22_multa)  as multa,                                                                 ";
    $sSqlResumoGeral .= " 			 sum(debitos.k22_vlrcor+debitos.k22_juros+debitos.k22_multa) as total                              ";
    $sSqlResumoGeral .= "   from debitos                                                                                           ";
    $sSqlResumoGeral .= " 	     inner join divida     on divida.v01_numpre = debitos.k22_numpre                                   ";
    $sSqlResumoGeral .= " 	                          and divida.v01_numpar = debitos.k22_numpar                                   ";
    $sSqlResumoGeral .= "                             and divida.v01_instit = {$iInstituicao}                                      ";
    $sSqlResumoGeral .= "        inner join proced     on proced.v03_codigo = divida.v01_proced                                    ";
    $sSqlResumoGeral .= "        inner join tipoproced on tipoproced.v07_sequencial    = proced.v03_tributaria                     ";
    $sSqlResumoGeral .= "        inner join arretipo   on arretipo.k00_tipo = debitos.k22_tipo                                     ";
    $sSqlResumoGeral .= "        inner join tabrec     on tabrec.k02_codigo = debitos.k22_receit                                   ";
    $sSqlResumoGeral .= "        inner join cadtipo    on cadtipo.k03_tipo  = debitos.k22_tipo                                     ";
    $sSqlResumoGeral .= "  where arretipo.k03_tipo = 5                                                                             ";

    if ($sListaExercicios != '') {
      $sSqlResumoGeral .= "    and divida.v01_exerc in ({$sListaExercicios})                                                         ";
    }

    $sSqlResumoGeral .= "    and debitos.k22_data   = '{$dDataDebitos}'                                                            ";
    $sSqlResumoGeral .= "    and debitos.k22_instit = {$iInstituicao}                                                              ";
    $sSqlResumoGeral .= "  group by divida.v01_exerc,                                                                              ";
    $sSqlResumoGeral .= "           divida.v01_proced,                                                                             ";
    $sSqlResumoGeral .= "           divida.v01_dtvenc,                                                                             ";
    $sSqlResumoGeral .= "           k22_receit,                                                                                    ";
    $sSqlResumoGeral .= "           k02_descr,                                                                                     ";
    $sSqlResumoGeral .= "           cadtipo.k03_descr,                                                                             ";
    $sSqlResumoGeral .= "           cadtipo.k03_tipo,                                                                              ";
    $sSqlResumoGeral .= "           proced.v03_descr,                                                                              ";
    $sSqlResumoGeral .= "           proced.v03_tributaria,                                                                         ";
    $sSqlResumoGeral .= "           tipoproced.v07_descricao                                                                       ";

    // 2º Parcelamentos e Reparcelamentos de Divida
    $sSqlResumoGeral .= " union all                                                                                                ";
    $sSqlResumoGeral .= " select v01_exerc,                                                                                        ";
    $sSqlResumoGeral .= " 		   v01_proced,                                                                                       ";
    $sSqlResumoGeral .= "        debitos.k03_tipo,                                                                                 ";
    $sSqlResumoGeral .= "  	     v03_tributaria,                                                                                   ";
    $sSqlResumoGeral .= "        k22_receit        as receit,                                                                      ";
    $sSqlResumoGeral .= "        divida.v01_dtvenc as dtvenc,                                                                      ";
    $sSqlResumoGeral .= "        debitos.k03_descr as descrtipo,                                                                   ";
    $sSqlResumoGeral .= "        proced.v03_descr  as descrproced,                                                                 ";
    $sSqlResumoGeral .= "        k02_descr         as descrreceit,                                                                 ";
    $sSqlResumoGeral .= "        v07_descricao     as descrtipoproced,                                                             ";
    $sSqlResumoGeral .= "        sum( (divida.v01_vlrhis/rr.total*100)/100 * k22_vlrhis::float  ) as vlrhis,                       ";
    $sSqlResumoGeral .= "        sum( (divida.v01_vlrhis/rr.total*100)/100 * k22_vlrcor::float  ) as vlrcor,                       ";
    $sSqlResumoGeral .= "        sum( (divida.v01_vlrhis/rr.total*100)/100 * k22_juros::float   ) as juros,                        ";
    $sSqlResumoGeral .= "        sum( (divida.v01_vlrhis/rr.total*100)/100 * k22_multa::float   ) as multa,                        ";
    $sSqlResumoGeral .= "        sum( (divida.v01_vlrhis/rr.total*100)/100 * valor_total::float ) as total                         ";
    $sSqlResumoGeral .= "   from termodiv                                                                                          ";
    // Junta dividas do termo
    $sSqlResumoGeral .= "        inner join divida     on v01_coddiv        = termodiv.coddiv                                      ";
    $sSqlResumoGeral .= "                             and divida.v01_instit = {$iInstituicao}                                      ";

    if ($sListaExercicios != '') {
      $sSqlResumoGeral .= "                             and divida.v01_exerc in ( {$sListaExercicios} )                              ";
    }

    $sSqlResumoGeral .= " 	   	 inner join proced     on v01_proced        = v03_codigo                                           ";
    $sSqlResumoGeral .= "        inner join tipoproced on v07_sequencial    = v03_tributaria                                       ";
    // Parcelamentos na Debitos
    $sSqlResumoGeral .= "            inner join w_parcelamentos_corrigidos as debitos on debitos.v07_parcel = termodiv.parcel      ";
    // Somatorio do Valor total das Dividas do Termo
    $sSqlResumoGeral .= "            inner join ( select parcel,                                                                   ";
    $sSqlResumoGeral .= "                                sum(coalesce(v01_vlrhis,0)) as total                                      ";
    $sSqlResumoGeral .= "                           from termodiv                                                                  ";
    $sSqlResumoGeral .= "                                inner join divida  on v01_coddiv        = termodiv.coddiv                 ";
    $sSqlResumoGeral .= "                                                  and divida.v01_instit = {$iInstituicao}                 ";
    $sSqlResumoGeral .= "                          where divida.v01_instit = {$iInstituicao}                                       ";

    if ($sListaExercicios != '') {
      $sSqlResumoGeral .= "                            and divida.v01_exerc in ( {$sListaExercicios} )                               ";
    }

    $sSqlResumoGeral .= "                       group by parcel ) rr on rr.parcel = termodiv.parcel                                ";
    $sSqlResumoGeral .= "     group by v01_exerc,                                                                                  ";
    $sSqlResumoGeral .= "              v01_proced,                                                                                 ";
    $sSqlResumoGeral .= "              debitos.k03_tipo,                                                                           ";
    $sSqlResumoGeral .= "              v03_tributaria,                                                                             ";
    $sSqlResumoGeral .= "              k22_receit,                                                                                 ";
    $sSqlResumoGeral .= "              k02_descr,                                                                                  ";
    $sSqlResumoGeral .= "              divida.v01_dtvenc,                                                                          ";
    $sSqlResumoGeral .= "              debitos.k03_descr,                                                                          ";
    $sSqlResumoGeral .= "              proced.v03_descr,                                                                           ";
    $sSqlResumoGeral .= "              v07_descricao                                                                               ";

    // 4º Certidao/Inicial Foro ( Parcelamento )
    $sSqlResumoGeral .= " union all ";

    $sSqlResumoGeral .= " select inicialcert.v01_exerc,                                                                            ";
    $sSqlResumoGeral .= "        inicialcert.v01_proced,                                                                           ";
    $sSqlResumoGeral .= "        k03_tipo,                                                                                         ";
    $sSqlResumoGeral .= "        v03_tributaria,                                                                                   ";
    $sSqlResumoGeral .= "        k22_receit             as receit,                                                                 ";
    $sSqlResumoGeral .= "        inicialcert.v01_dtvenc as dtvenc,                                                                 ";
    $sSqlResumoGeral .= "        k03_descr              as descrtipo,                                                              ";
    $sSqlResumoGeral .= "        v03_descr              as descrproced,                                                            ";
    $sSqlResumoGeral .= "        k02_descr              as descrreceit,                                                            ";
    $sSqlResumoGeral .= "        v07_descricao          as descrtipoproced,                                                        ";
    $sSqlResumoGeral .= "        sum( ( inicialcert.v01_valor /parctotal.totalparc*100 )/100 * k22_vlrhis ::float ) as vlrhis,     ";
    $sSqlResumoGeral .= "        sum( ( inicialcert.v01_valor /parctotal.totalparc*100 )/100 * k22_vlrcor ::float ) as vlrcor,     ";
    $sSqlResumoGeral .= "        sum( ( inicialcert.v01_valor /parctotal.totalparc*100 )/100 * k22_juros  ::float ) as juros,      ";
    $sSqlResumoGeral .= "        sum( ( inicialcert.v01_valor /parctotal.totalparc*100 )/100 * k22_multa  ::float ) as multa,      ";
    $sSqlResumoGeral .= "        sum( ( inicialcert.v01_valor /parctotal.totalparc*100 )/100 * valor_total::float ) as total       ";
    $sSqlResumoGeral .= "   from termoini                                                                                          ";
    $sSqlResumoGeral .= "        inner join w_parcelamentos_corrigidos as debitos on debitos.v07_parcel = termoini.parcel          ";
    $sSqlResumoGeral .= "        inner join ( select v01_exerc,                                                                    ";
    $sSqlResumoGeral .= "                            v01_proced,                                                                   ";
    $sSqlResumoGeral .= "                            v01_dtvenc,                                                                   ";
    $sSqlResumoGeral .= "                            inicial,                                                                      ";
    $sSqlResumoGeral .= "                            parcel,                                                                       ";
    $sSqlResumoGeral .= "                            v03_tributaria,                                                               ";
    $sSqlResumoGeral .= "                            v07_descricao,                                                                ";
    $sSqlResumoGeral .= "                            v03_descr,                                                                    ";
    $sSqlResumoGeral .= "                            sum(v01_vlrhis) as v01_valor                                                  ";
    $sSqlResumoGeral .= "                       from w_certidao_dividas                                                            ";
    $sSqlResumoGeral .= "                            inner join proced     on v03_codigo     = v01_proced                          ";
    $sSqlResumoGeral .= "                            inner join tipoproced on v07_sequencial = v03_tributaria                      ";
    $sSqlResumoGeral .= "                      group by v01_exerc,                                                                 ";
    $sSqlResumoGeral .= "                               v01_proced,                                                                ";
    $sSqlResumoGeral .= "                               v01_dtvenc,                                                                ";
    $sSqlResumoGeral .= "                               inicial,                                                                   ";
    $sSqlResumoGeral .= "                               parcel,                                                                    ";
    $sSqlResumoGeral .= "                               v03_tributaria,                                                            ";
    $sSqlResumoGeral .= "                               v07_descricao,                                                             ";
    $sSqlResumoGeral .= "                               v03_descr                                                                  ";
    $sSqlResumoGeral .= "                   ) as inicialcert on termoini.inicial = inicialcert.inicial                             ";
    $sSqlResumoGeral .= "                                   and termoini.parcel  = inicialcert.parcel                              ";
    $sSqlResumoGeral .= "       inner join ( select parcel,                                                                        ";
    $sSqlResumoGeral .= "                           sum(v01_vlrhis) as totalparc                                                   ";
    $sSqlResumoGeral .= "                      from w_certidao_dividas                                                             ";
    $sSqlResumoGeral .= "                     group by parcel                                                                      ";
    $sSqlResumoGeral .= "                  ) as parctotal on termoini.parcel = parctotal.parcel                                    ";
    $sSqlResumoGeral .= " group by inicialcert.v01_exerc,                                                                          ";
    $sSqlResumoGeral .= "          inicialcert.v01_proced,                                                                         ";
    $sSqlResumoGeral .= "          inicialcert.v01_dtvenc,                                                                         ";
    $sSqlResumoGeral .= "          k22_receit,                                                                                     ";
    $sSqlResumoGeral .= "          k02_descr,                                                                                      ";
    $sSqlResumoGeral .= "          k03_tipo,                                                                                       ";
    $sSqlResumoGeral .= "          v03_tributaria,                                                                                 ";
    $sSqlResumoGeral .= "          k03_descr,                                                                                      ";
    $sSqlResumoGeral .= "          v03_descr,                                                                                      ";
    $sSqlResumoGeral .= "          v07_descricao                                                                                   ";

    // 3º Certidão de Dívida
    $sSqlResumoGeral .= " union all ";

    $sSqlResumoGeral .= " select v01_exerc,                                                                                        ";
    $sSqlResumoGeral .= "        v01_proced,                                                                                       ";
    $sSqlResumoGeral .= "        cadtipo.k03_tipo,                                                                                 ";
    $sSqlResumoGeral .= "        v03_tributaria,                                                                                   ";
    $sSqlResumoGeral .= "        k22_receit                                   as receit,                                           ";
    $sSqlResumoGeral .= "        v01_dtvenc                                   as dtvenc,                                           ";
    $sSqlResumoGeral .= "        k03_descr                                    as descrtipo,                                        ";
    $sSqlResumoGeral .= "        proced.v03_descr                             as descrproced,                                      ";
    $sSqlResumoGeral .= "        k02_descr                                    as descrreceit,                                      ";
    $sSqlResumoGeral .= "        v07_descricao                                as descrtipoproced,                                  ";
    $sSqlResumoGeral .= "        sum(k22_vlrhis)                     as vlrhis,                                                    ";
    $sSqlResumoGeral .= "        sum(k22_vlrcor)                     as vlrcor,                                                    ";
    $sSqlResumoGeral .= "        sum(k22_juros)                      as juros,                                                     ";
    $sSqlResumoGeral .= "        sum(k22_multa)                      as multa,                                                     ";
    $sSqlResumoGeral .= "        sum(k22_vlrcor+k22_juros+k22_multa) as total                                                      ";
    $sSqlResumoGeral .= "     from debitos                                                                                         ";
    $sSqlResumoGeral .= "          inner join (select distinct v01_exerc,                                                          ";
    $sSqlResumoGeral .= "                                      v01_proced,                                                         ";
    $sSqlResumoGeral .= "                                      v01_numpre,                                                         ";
    $sSqlResumoGeral .= "                                      v01_numpar,                                                         ";
    $sSqlResumoGeral .= "                                      v01_dtvenc                                                          ";
    $sSqlResumoGeral .= "                                 from w_certidao_dividas                                                  ";
    $sSqlResumoGeral .= "                                 ) as origem_divida on origem_divida.v01_numpre = debitos.k22_numpre      ";
    $sSqlResumoGeral .= "                                                   and origem_divida.v01_numpar = debitos.k22_numpar      ";
    $sSqlResumoGeral .= "          inner join proced     on v01_proced        = v03_codigo                                         ";
    $sSqlResumoGeral .= "          inner join tipoproced on v07_sequencial    = v03_tributaria                                     ";
    $sSqlResumoGeral .= "          inner join tabrec     on tabrec.k02_codigo = debitos.k22_receit                                 ";
    $sSqlResumoGeral .= "          inner join arretipo   on arretipo.k00_tipo = k22_tipo                                           ";
    $sSqlResumoGeral .= "          inner join cadtipo    on cadtipo.k03_tipo  = arretipo.k03_tipo                                  ";
    $sSqlResumoGeral .= "    where k22_instit = {$iInstituicao}                                                                    ";

    if ($sListaExercicios != '') {
      $sSqlResumoGeral .= "      and v01_exerc in ( {$sListaExercicios} )                                                            ";
    }

    $sSqlResumoGeral .= "      and k22_data = '{$dDataDebitos}'                                                                    ";
    $sSqlResumoGeral .= "    group by v01_exerc,                                                                                   ";
    $sSqlResumoGeral .= "             v01_proced,                                                                                  ";
    $sSqlResumoGeral .= "             cadtipo.k03_tipo,                                                                            ";
    $sSqlResumoGeral .= "             v03_tributaria,                                                                              ";
    $sSqlResumoGeral .= "             k22_receit,                                                                                  ";
    $sSqlResumoGeral .= "             k02_descr,                                                                                   ";
    $sSqlResumoGeral .= "             v01_dtvenc,                                                                                  ";
    $sSqlResumoGeral .= "             k03_descr,                                                                                   ";
    $sSqlResumoGeral .= "             proced.v03_descr,                                                                            ";
    $sSqlResumoGeral .= "             v07_descricao                                                                                ";

    return $sSqlResumoGeral;
  }

  public function sql_queryDebitosAnteriores ($dDataDebitos) {

    // Cria lista com exercícios de 3 anos anteriores aos exercícios selecionados
    $aDataDebitos = explode("-",$dDataDebitos);

    for ( $iInd=1; $iInd < 4; $iInd++ ) {
      $aExercicioPago[] = ($aDataDebitos[0] - $iInd);
    }

    $aExercicioPago = array_unique($aExercicioPago);

    $sSqlDebitosPago  = " select arretipo.k03_tipo,                                                            ";
    $sSqlDebitosPago .= "        arrecant.k00_receit as receit,                                                ";
    $sSqlDebitosPago .= "        divida.v01_proced,                                                            ";
    $sSqlDebitosPago .= "        divida.v01_exerc,                                                             ";
    $sSqlDebitosPago .= "        v03_tributaria,                                                               ";
    $sSqlDebitosPago .= "        round(sum(arrepaga.k00_valor),2) as total                                     ";
    $sSqlDebitosPago .= "   from divida                                                                        ";
    $sSqlDebitosPago .= "        inner join arrepaga on arrepaga.k00_numpre = divida.v01_numpre                ";
    $sSqlDebitosPago .= "                           and arrepaga.k00_numpar = divida.v01_numpar                ";
    $sSqlDebitosPago .= "        inner join arrecant on arrecant.k00_numpre = divida.v01_numpre                ";
    $sSqlDebitosPago .= "                           and arrecant.k00_numpar = divida.v01_numpar                ";
    $sSqlDebitosPago .= "        inner join arretipo on arretipo.k00_tipo   = arrecant.k00_tipo                ";
    $sSqlDebitosPago .= "        inner join proced   on proced.v03_codigo   = divida.v01_proced                ";
    $sSqlDebitosPago .= "  where extract( year from arrepaga.k00_dtpaga) in (".implode(',',$aExercicioPago).") ";
    $sSqlDebitosPago .= "  group by arretipo.k03_tipo,                                                         ";
    $sSqlDebitosPago .= "           arrecant.k00_receit,                                                       ";
    $sSqlDebitosPago .= "           divida.v01_proced,                                                         ";
    $sSqlDebitosPago .= "           divida.v01_exerc,                                                          ";
    $sSqlDebitosPago .= "           v03_tributaria                                                             ";
    $sSqlDebitosPago .= "  order by arretipo.k03_tipo,                                                         ";
    $sSqlDebitosPago .= "           divida.v01_exerc,                                                          ";
    $sSqlDebitosPago .= "           divida.v01_proced;                                                         ";

    return $sSqlDebitosPago;
  }

  public function processamentoResumoGeralDivida ($dDataDebitos) {

    $aResumoCurtoPrazo = array();
    $aResumoLongoPrazo = array();

    $aAgrupador['receita'] = 'receit';

    $aDataDebitos       = explode("-", $dDataDebitos);

    $sSqlDebitosPagos   = $this->sql_queryDebitosAnteriores($aDataDebitos[0]);
    $rsDebitosPagos     = $this->sql_record($sSqlDebitosPagos);

    for ( $iInd = 0; $iInd < $this->numrows; $iInd++ ) {

      $oDebitosPagos = db_utils::fieldsMemory($rsDebitosPagos, $iInd);

      if ( isset($aDebitosPagos[$oDebitosPagos->k03_tipo][$oDebitosPagos->v01_proced]) ) {
        $aDebitosPagos[$oDebitosPagos->k03_tipo][$oDebitosPagos->v01_proced]['nTotal'] += $oDebitosPagos->total;
      } else {
        $aDebitosPagos[$oDebitosPagos->k03_tipo][$oDebitosPagos->v01_proced]['nTotal']  = $oDebitosPagos->total;
      }

      foreach ( $aAgrupador as $sDescrAgrupa => $sCampo ) {

        if ( isset($aResumoDebitosPagos[$sDescrAgrupa][$oDebitosPagos->$sCampo]) ) {
          $aResumoDebitosPagos[$sDescrAgrupa][$oDebitosPagos->$sCampo]['nTotal'] += $oDebitosPagos->total;
        } else {
          $aResumoDebitosPagos[$sDescrAgrupa][$oDebitosPagos->$sCampo]['nTotal']  = $oDebitosPagos->total;
        }

      }

    }

    $sSqlResumoGeral    = $this->sql_queryProcessamentoResumoGeralDivida($dDataDebitos);
    $rsResumoGeral      = $this->sql_record($sSqlResumoGeral);
    $iLinhasResumoGeral = $this->numrows;

    $aLongoPrazo = array();
    $aCurtoPrazo = array();

    for ( $iInd=0; $iInd < $iLinhasResumoGeral; $iInd++ ) {

      $oResumo = db_utils::fieldsMemory($rsResumoGeral,$iInd);

      $dtDataLimite = ($oResumo->v01_exerc + 1)."-12-31";

      foreach ( $aAgrupador as $sDescrAgrupa => $sCampo ) {

        $aDescrTipo[$oResumo->k03_tipo]             = $oResumo->descrtipo;
        $aDescrProced[$oResumo->v01_proced]         = $oResumo->descrproced;
        $aDescrTipoProced[$oResumo->v03_tributaria] = $oResumo->descrtipoproced;

        if ( $sDescrAgrupa == 'proced' ) {
          $sDescricao = $oResumo->descrproced;
        } else if ( $sDescrAgrupa == 'tipo_proced' ) {
          $sDescricao = $oResumo->descrtipoproced;
        } else if ( $sDescrAgrupa == 'receita' ) {
          $sDescricao = $oResumo->descrreceit;
        } else {
          $sDescricao = $oResumo->descrtipo;
        }

        if (  in_array($oResumo->k03_tipo,array(5,15,18)) || ( in_array($oResumo->k03_tipo,array(6,13)) && $oResumo->dtvenc > $dtDataLimite ) ) {

          if ( isset($aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]) ) {

            $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nVlrHist']   += $oResumo->vlrhis;
            $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nVlrCorr']   += $oResumo->vlrcor;
            $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nMulta']     += $oResumo->multa;
            $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nJuros']     += $oResumo->juros;
            $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nTotal']     += $oResumo->total;
          } else {

            $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['sDescricao']  = $sDescricao;
            $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nVlrHist']    = $oResumo->vlrhis;
            $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nVlrCorr']    = $oResumo->vlrcor;
            $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nMulta']      = $oResumo->multa;
            $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nJuros']      = $oResumo->juros;
            $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nTotal']      = $oResumo->total;
          }

        } else if ( in_array($oResumo->k03_tipo,array(6,13)) && $oResumo->dtvenc <= $dtDataLimite ) {

          if ( isset($aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]) ) {

            $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nVlrHist']   += $oResumo->vlrhis;
            $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nVlrCorr']   += $oResumo->vlrcor;
            $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nMulta']     += $oResumo->multa;
            $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nJuros']     += $oResumo->juros;
            $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nTotal']     += $oResumo->total;
          } else {

            $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['sDescricao']  = $sDescricao;
            $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nVlrHist']    = $oResumo->vlrhis;
            $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nVlrCorr']    = $oResumo->vlrcor;
            $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nMulta']      = $oResumo->multa;
            $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nJuros']      = $oResumo->juros;
            $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nTotal']      = $oResumo->total;
          }

        }

      }
    }

    foreach ( $aResumoLongoPrazo as $sTipoAgrupa => $aDadosLongoPrazo ) {

      foreach ( $aDadosLongoPrazo as $sCampoAgrupa =>$aValoresLongoPrazo) {

        if ( isset($aResumoDebitosPagos[$sTipoAgrupa][$sCampoAgrupa])) {

          $nTotalPago   = $aResumoDebitosPagos[$sTipoAgrupa][$sCampoAgrupa]['nTotal'];
          $nTotalPago   = round(( ($nTotalPago/3) * 2 ),2);
          $nTotalProced = $aValoresLongoPrazo['nTotal'];

          // Percentual que será subtraído do logon prazo e incluído no longo prazo
          $nPercentual  = round(( ($nTotalPago*100) / $nTotalProced ),2);

          $nValorHist  = ( ($aValoresLongoPrazo['nVlrHist']/100) * $nPercentual );
          $nValorCorr  = ( ($aValoresLongoPrazo['nVlrCorr']/100) * $nPercentual );
          $nValorMulta = ( ($aValoresLongoPrazo['nMulta']/100) * $nPercentual );
          $nValorJuros = ( ($aValoresLongoPrazo['nJuros']/100) * $nPercentual );
          $nValorTotal = ( ($aValoresLongoPrazo['nTotal']/100) * $nPercentual );

          if ( $nValorTotal < $aValoresLongoPrazo['nTotal'] ) {

            $aResumoLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']  -= $nValorHist;
            $aResumoLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']  -= $nValorCorr;
            $aResumoLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']    -= $nValorMulta;
            $aResumoLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']    -= $nValorJuros;
            $aResumoLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']    -= $nValorTotal;

            if ( isset($aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]) ) {

              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']   += $nValorHist;
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']   += $nValorCorr;
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']     += $nValorMulta;
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']     += $nValorJuros;
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']     += $nValorTotal;
            } else {

              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['sDescricao'] = $aValoresLongoPrazo['sDescricao'];
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']   = $nValorHist;
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']   = $nValorCorr;
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']     = $nValorMulta;
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']     = $nValorJuros;
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']     = $nValorTotal;
            }

          } else {

            if ( isset($aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]) ) {

              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']   += $aValoresLongoPrazo['nVlrHist'];
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']   += $aValoresLongoPrazo['nVlrCorr'];
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']     += $aValoresLongoPrazo['nMulta'];
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']     += $aValoresLongoPrazo['nJuros'];
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']     += $aValoresLongoPrazo['nTotal'];
            } else {

              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['sDescricao'] = $aValoresLongoPrazo['sDescricao'];
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']   = $aValoresLongoPrazo['nVlrHist'];
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']   = $aValoresLongoPrazo['nVlrCorr'];
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']     = $aValoresLongoPrazo['nMulta'];
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']     = $aValoresLongoPrazo['nJuros'];
              $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']     = $aValoresLongoPrazo['nTotal'];
            }

            unset($aResumoLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]);
          }
        }
      }
    }

    foreach ( $aResumoLongoPrazo as $sTipoAgrupa => $aDadosLongoPrazo ) {

      if ( count($aDadosLongoPrazo) == 0 ) {
        unset($aResumoLongoPrazo[$sTipoAgrupa]);
      }
    }

    $aDados = array();
    $aDados['aCurtoPrazo'] = $aResumoCurtoPrazo;
    $aDados['aLongoPrazo'] = $aResumoLongoPrazo;

    return $aDados;
  }

}
?>