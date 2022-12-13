<?
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
//CLASSE DA ENTIDADE termo
class cl_termo {
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
   var $v07_parcel = 0;
   var $v07_dtlanc_dia = null;
   var $v07_dtlanc_mes = null;
   var $v07_dtlanc_ano = null;
   var $v07_dtlanc = null;
   var $v07_valor = 0;
   var $v07_numpre = 0;
   var $v07_totpar = 0;
   var $v07_vlrpar = 0;
   var $v07_dtvenc_dia = null;
   var $v07_dtvenc_mes = null;
   var $v07_dtvenc_ano = null;
   var $v07_dtvenc = null;
   var $v07_vlrent = 0;
   var $v07_datpri_dia = null;
   var $v07_datpri_mes = null;
   var $v07_datpri_ano = null;
   var $v07_datpri = null;
   var $v07_vlrmul = 0;
   var $v07_vlrjur = 0;
   var $v07_perjur = 0;
   var $v07_permul = 0;
   var $v07_login = 0;
   var $v07_mtermo = 0;
   var $v07_numcgm = 0;
   var $v07_hist = null;
   var $v07_ultpar = 0;
   var $v07_desconto = 0;
   var $v07_descjur = 0;
   var $v07_descmul = 0;
   var $v07_situacao = 0;
   var $v07_instit = 0;
   var $v07_vlrhis = 0;
   var $v07_vlrcor = 0;
   var $v07_vlrdes = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 v07_parcel = int4 = Parcelamento 
                 v07_dtlanc = date = data de lancamento do parcelamento 
                 v07_valor = float8 = valor do parcelamento 
                 v07_numpre = int4 = numpre do parcelamento 
                 v07_totpar = int4 = total de parcelas 
                 v07_vlrpar = float8 = valor das parcelas 
                 v07_dtvenc = date = data de vencimento 
                 v07_vlrent = float8 = valor da entrada 
                 v07_datpri = date = data da primeira parcela 
                 v07_vlrmul = float8 = valor da multa 
                 v07_vlrjur = float8 = valor dos juros 
                 v07_perjur = float8 = percentual dos juros 
                 v07_permul = float8 = percentual das multas 
                 v07_login = int4 = login 
                 v07_mtermo = oid = termo 
                 v07_numcgm = int4 = Responsável pelo parcelamento 
                 v07_hist = varchar(130) = historico 
                 v07_ultpar = float8 = Valor da ultima parcela 
                 v07_desconto = int4 = Código do desconto 
                 v07_descjur = float8 = Desconto nos juros 
                 v07_descmul = float8 = Desconto na multa 
                 v07_situacao = int4 = Situacao 
                 v07_instit = int4 = Cod. Instituição 
                 v07_vlrhis = float8 = Valor Histórico 
                 v07_vlrcor = float8 = Valor Corrigido 
                 v07_vlrdes = float8 = Valor Desconto 
                 ";
   //funcao construtor da classe
   function cl_termo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("termo");
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
       $this->v07_parcel = ($this->v07_parcel == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_parcel"]:$this->v07_parcel);
       if($this->v07_dtlanc == ""){
         $this->v07_dtlanc_dia = ($this->v07_dtlanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_dtlanc_dia"]:$this->v07_dtlanc_dia);
         $this->v07_dtlanc_mes = ($this->v07_dtlanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_dtlanc_mes"]:$this->v07_dtlanc_mes);
         $this->v07_dtlanc_ano = ($this->v07_dtlanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_dtlanc_ano"]:$this->v07_dtlanc_ano);
         if($this->v07_dtlanc_dia != ""){
            $this->v07_dtlanc = $this->v07_dtlanc_ano."-".$this->v07_dtlanc_mes."-".$this->v07_dtlanc_dia;
         }
       }
       $this->v07_valor = ($this->v07_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_valor"]:$this->v07_valor);
       $this->v07_numpre = ($this->v07_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_numpre"]:$this->v07_numpre);
       $this->v07_totpar = ($this->v07_totpar == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_totpar"]:$this->v07_totpar);
       $this->v07_vlrpar = ($this->v07_vlrpar == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_vlrpar"]:$this->v07_vlrpar);
       if($this->v07_dtvenc == ""){
         $this->v07_dtvenc_dia = ($this->v07_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_dtvenc_dia"]:$this->v07_dtvenc_dia);
         $this->v07_dtvenc_mes = ($this->v07_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_dtvenc_mes"]:$this->v07_dtvenc_mes);
         $this->v07_dtvenc_ano = ($this->v07_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_dtvenc_ano"]:$this->v07_dtvenc_ano);
         if($this->v07_dtvenc_dia != ""){
            $this->v07_dtvenc = $this->v07_dtvenc_ano."-".$this->v07_dtvenc_mes."-".$this->v07_dtvenc_dia;
         }
       }
       $this->v07_vlrent = ($this->v07_vlrent == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_vlrent"]:$this->v07_vlrent);
       if($this->v07_datpri == ""){
         $this->v07_datpri_dia = ($this->v07_datpri_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_datpri_dia"]:$this->v07_datpri_dia);
         $this->v07_datpri_mes = ($this->v07_datpri_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_datpri_mes"]:$this->v07_datpri_mes);
         $this->v07_datpri_ano = ($this->v07_datpri_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_datpri_ano"]:$this->v07_datpri_ano);
         if($this->v07_datpri_dia != ""){
            $this->v07_datpri = $this->v07_datpri_ano."-".$this->v07_datpri_mes."-".$this->v07_datpri_dia;
         }
       }
       $this->v07_vlrmul = ($this->v07_vlrmul == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_vlrmul"]:$this->v07_vlrmul);
       $this->v07_vlrjur = ($this->v07_vlrjur == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_vlrjur"]:$this->v07_vlrjur);
       $this->v07_perjur = ($this->v07_perjur == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_perjur"]:$this->v07_perjur);
       $this->v07_permul = ($this->v07_permul == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_permul"]:$this->v07_permul);
       $this->v07_login = ($this->v07_login == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_login"]:$this->v07_login);
       $this->v07_mtermo = ($this->v07_mtermo == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_mtermo"]:$this->v07_mtermo);
       $this->v07_numcgm = ($this->v07_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_numcgm"]:$this->v07_numcgm);
       $this->v07_hist = ($this->v07_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_hist"]:$this->v07_hist);
       $this->v07_ultpar = ($this->v07_ultpar == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_ultpar"]:$this->v07_ultpar);
       $this->v07_desconto = ($this->v07_desconto == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_desconto"]:$this->v07_desconto);
       $this->v07_descjur = ($this->v07_descjur == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_descjur"]:$this->v07_descjur);
       $this->v07_descmul = ($this->v07_descmul == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_descmul"]:$this->v07_descmul);
       $this->v07_situacao = ($this->v07_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_situacao"]:$this->v07_situacao);
       $this->v07_instit = ($this->v07_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_instit"]:$this->v07_instit);
       $this->v07_vlrhis = ($this->v07_vlrhis == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_vlrhis"]:$this->v07_vlrhis);
       $this->v07_vlrcor = ($this->v07_vlrcor == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_vlrcor"]:$this->v07_vlrcor);
       $this->v07_vlrdes = ($this->v07_vlrdes == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_vlrdes"]:$this->v07_vlrdes);
     }else{
       $this->v07_parcel = ($this->v07_parcel == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_parcel"]:$this->v07_parcel);
     }
   }
   // funcao para inclusao
   function incluir ($v07_parcel){
      $this->atualizacampos();
     if($this->v07_dtlanc == null ){
       $this->erro_sql = " Campo data de lancamento do parcelamento nao Informado.";
       $this->erro_campo = "v07_dtlanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_valor == null ){
       $this->erro_sql = " Campo valor do parcelamento nao Informado.";
       $this->erro_campo = "v07_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_numpre == null ){
       $this->erro_sql = " Campo numpre do parcelamento nao Informado.";
       $this->erro_campo = "v07_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_totpar == null ){
       $this->erro_sql = " Campo total de parcelas nao Informado.";
       $this->erro_campo = "v07_totpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_vlrpar == null ){
       $this->erro_sql = " Campo valor das parcelas nao Informado.";
       $this->erro_campo = "v07_vlrpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_dtvenc == null ){
       $this->erro_sql = " Campo data de vencimento nao Informado.";
       $this->erro_campo = "v07_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_vlrent == null ){
       $this->erro_sql = " Campo valor da entrada nao Informado.";
       $this->erro_campo = "v07_vlrent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_datpri == null ){
       $this->erro_sql = " Campo data da primeira parcela nao Informado.";
       $this->erro_campo = "v07_datpri_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_vlrmul == null ){
       $this->erro_sql = " Campo valor da multa nao Informado.";
       $this->erro_campo = "v07_vlrmul";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_vlrjur == null ){
       $this->erro_sql = " Campo valor dos juros nao Informado.";
       $this->erro_campo = "v07_vlrjur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_perjur == null ){
       $this->erro_sql = " Campo percentual dos juros nao Informado.";
       $this->erro_campo = "v07_perjur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_permul == null ){
       $this->erro_sql = " Campo percentual das multas nao Informado.";
       $this->erro_campo = "v07_permul";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_login == null ){
       $this->erro_sql = " Campo login nao Informado.";
       $this->erro_campo = "v07_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_mtermo == null ){
       $this->erro_sql = " Campo termo nao Informado.";
       $this->erro_campo = "v07_mtermo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_numcgm == null ){
       $this->erro_sql = " Campo Responsável pelo parcelamento nao Informado.";
       $this->erro_campo = "v07_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_hist == null ){
       $this->erro_sql = " Campo historico nao Informado.";
       $this->erro_campo = "v07_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_ultpar == null ){
       $this->erro_sql = " Campo Valor da ultima parcela nao Informado.";
       $this->erro_campo = "v07_ultpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_desconto == null ){
       $this->erro_sql = " Campo Código do desconto nao Informado.";
       $this->erro_campo = "v07_desconto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_descjur == null ){
       $this->erro_sql = " Campo Desconto nos juros nao Informado.";
       $this->erro_campo = "v07_descjur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_descmul == null ){
       $this->erro_sql = " Campo Desconto na multa nao Informado.";
       $this->erro_campo = "v07_descmul";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_situacao == null ){
       $this->erro_sql = " Campo Situacao nao Informado.";
       $this->erro_campo = "v07_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_instit == null ){
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "v07_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_vlrhis == null ){
       $this->erro_sql = " Campo Valor Histórico nao Informado.";
       $this->erro_campo = "v07_vlrhis";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_vlrcor == null ){
       $this->erro_sql = " Campo Valor Corrigido nao Informado.";
       $this->erro_campo = "v07_vlrcor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_vlrdes == null ){
       $this->erro_sql = " Campo Valor Desconto nao Informado.";
       $this->erro_campo = "v07_vlrdes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v07_parcel == "" || $v07_parcel == null ){
       $result = db_query("select nextval('termo_v07_parcel_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: termo_v07_parcel_seq do campo: v07_parcel";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->v07_parcel = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from termo_v07_parcel_seq");
       if(($result != false) && (pg_result($result,0,0) < $v07_parcel)){
         $this->erro_sql = " Campo v07_parcel maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v07_parcel = $v07_parcel;
       }
     }
     if(($this->v07_parcel == null) || ($this->v07_parcel == "") ){
       $this->erro_sql = " Campo v07_parcel nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into termo(
                                       v07_parcel 
                                      ,v07_dtlanc 
                                      ,v07_valor 
                                      ,v07_numpre 
                                      ,v07_totpar 
                                      ,v07_vlrpar 
                                      ,v07_dtvenc 
                                      ,v07_vlrent 
                                      ,v07_datpri 
                                      ,v07_vlrmul 
                                      ,v07_vlrjur 
                                      ,v07_perjur 
                                      ,v07_permul 
                                      ,v07_login 
                                      ,v07_mtermo 
                                      ,v07_numcgm 
                                      ,v07_hist 
                                      ,v07_ultpar 
                                      ,v07_desconto 
                                      ,v07_descjur 
                                      ,v07_descmul 
                                      ,v07_situacao 
                                      ,v07_instit 
                                      ,v07_vlrhis 
                                      ,v07_vlrcor 
                                      ,v07_vlrdes 
                       )
                values (
                                $this->v07_parcel 
                               ,".($this->v07_dtlanc == "null" || $this->v07_dtlanc == ""?"null":"'".$this->v07_dtlanc."'")." 
                               ,$this->v07_valor 
                               ,$this->v07_numpre 
                               ,$this->v07_totpar 
                               ,$this->v07_vlrpar 
                               ,".($this->v07_dtvenc == "null" || $this->v07_dtvenc == ""?"null":"'".$this->v07_dtvenc."'")." 
                               ,$this->v07_vlrent 
                               ,".($this->v07_datpri == "null" || $this->v07_datpri == ""?"null":"'".$this->v07_datpri."'")." 
                               ,$this->v07_vlrmul 
                               ,$this->v07_vlrjur 
                               ,$this->v07_perjur 
                               ,$this->v07_permul 
                               ,$this->v07_login 
                               ,$this->v07_mtermo 
                               ,$this->v07_numcgm 
                               ,'$this->v07_hist' 
                               ,$this->v07_ultpar 
                               ,$this->v07_desconto 
                               ,$this->v07_descjur 
                               ,$this->v07_descmul 
                               ,$this->v07_situacao 
                               ,$this->v07_instit 
                               ,$this->v07_vlrhis 
                               ,$this->v07_vlrcor 
                               ,$this->v07_vlrdes 
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->v07_parcel) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->v07_parcel) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v07_parcel;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v07_parcel));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,537,'$this->v07_parcel','I')");
       $resac = db_query("insert into db_acount values($acount,103,537,'','".AddSlashes(pg_result($resaco,0,'v07_parcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,538,'','".AddSlashes(pg_result($resaco,0,'v07_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,539,'','".AddSlashes(pg_result($resaco,0,'v07_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,540,'','".AddSlashes(pg_result($resaco,0,'v07_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,541,'','".AddSlashes(pg_result($resaco,0,'v07_totpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,542,'','".AddSlashes(pg_result($resaco,0,'v07_vlrpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,543,'','".AddSlashes(pg_result($resaco,0,'v07_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,544,'','".AddSlashes(pg_result($resaco,0,'v07_vlrent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,545,'','".AddSlashes(pg_result($resaco,0,'v07_datpri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,546,'','".AddSlashes(pg_result($resaco,0,'v07_vlrmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,547,'','".AddSlashes(pg_result($resaco,0,'v07_vlrjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,548,'','".AddSlashes(pg_result($resaco,0,'v07_perjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,549,'','".AddSlashes(pg_result($resaco,0,'v07_permul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,550,'','".AddSlashes(pg_result($resaco,0,'v07_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,551,'','".AddSlashes(pg_result($resaco,0,'v07_mtermo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,552,'','".AddSlashes(pg_result($resaco,0,'v07_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,553,'','".AddSlashes(pg_result($resaco,0,'v07_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,8641,'','".AddSlashes(pg_result($resaco,0,'v07_ultpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,8642,'','".AddSlashes(pg_result($resaco,0,'v07_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,8643,'','".AddSlashes(pg_result($resaco,0,'v07_descjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,8644,'','".AddSlashes(pg_result($resaco,0,'v07_descmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,9552,'','".AddSlashes(pg_result($resaco,0,'v07_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,10577,'','".AddSlashes(pg_result($resaco,0,'v07_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,10786,'','".AddSlashes(pg_result($resaco,0,'v07_vlrhis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,10787,'','".AddSlashes(pg_result($resaco,0,'v07_vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,103,10788,'','".AddSlashes(pg_result($resaco,0,'v07_vlrdes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($v07_parcel=null) {
      $this->atualizacampos();
     $sql = " update termo set ";
     $virgula = "";
     if(trim($this->v07_parcel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_parcel"])){
       $sql  .= $virgula." v07_parcel = $this->v07_parcel ";
       $virgula = ",";
       if(trim($this->v07_parcel) == null ){
         $this->erro_sql = " Campo Parcelamento nao Informado.";
         $this->erro_campo = "v07_parcel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_dtlanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_dtlanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v07_dtlanc_dia"] !="") ){
       $sql  .= $virgula." v07_dtlanc = '$this->v07_dtlanc' ";
       $virgula = ",";
       if(trim($this->v07_dtlanc) == null ){
         $this->erro_sql = " Campo data de lancamento do parcelamento nao Informado.";
         $this->erro_campo = "v07_dtlanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v07_dtlanc_dia"])){
         $sql  .= $virgula." v07_dtlanc = null ";
         $virgula = ",";
         if(trim($this->v07_dtlanc) == null ){
           $this->erro_sql = " Campo data de lancamento do parcelamento nao Informado.";
           $this->erro_campo = "v07_dtlanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v07_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_valor"])){
       $sql  .= $virgula." v07_valor = $this->v07_valor ";
       $virgula = ",";
       if(trim($this->v07_valor) == null ){
         $this->erro_sql = " Campo valor do parcelamento nao Informado.";
         $this->erro_campo = "v07_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_numpre"])){
       $sql  .= $virgula." v07_numpre = $this->v07_numpre ";
       $virgula = ",";
       if(trim($this->v07_numpre) == null ){
         $this->erro_sql = " Campo numpre do parcelamento nao Informado.";
         $this->erro_campo = "v07_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_totpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_totpar"])){
       $sql  .= $virgula." v07_totpar = $this->v07_totpar ";
       $virgula = ",";
       if(trim($this->v07_totpar) == null ){
         $this->erro_sql = " Campo total de parcelas nao Informado.";
         $this->erro_campo = "v07_totpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_vlrpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrpar"])){
       $sql  .= $virgula." v07_vlrpar = $this->v07_vlrpar ";
       $virgula = ",";
       if(trim($this->v07_vlrpar) == null ){
         $this->erro_sql = " Campo valor das parcelas nao Informado.";
         $this->erro_campo = "v07_vlrpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v07_dtvenc_dia"] !="") ){
       $sql  .= $virgula." v07_dtvenc = '$this->v07_dtvenc' ";
       $virgula = ",";
       if(trim($this->v07_dtvenc) == null ){
         $this->erro_sql = " Campo data de vencimento nao Informado.";
         $this->erro_campo = "v07_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v07_dtvenc_dia"])){
         $sql  .= $virgula." v07_dtvenc = null ";
         $virgula = ",";
         if(trim($this->v07_dtvenc) == null ){
           $this->erro_sql = " Campo data de vencimento nao Informado.";
           $this->erro_campo = "v07_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v07_vlrent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrent"])){
       $sql  .= $virgula." v07_vlrent = $this->v07_vlrent ";
       $virgula = ",";
       if(trim($this->v07_vlrent) == null ){
         $this->erro_sql = " Campo valor da entrada nao Informado.";
         $this->erro_campo = "v07_vlrent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_datpri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_datpri_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v07_datpri_dia"] !="") ){
       $sql  .= $virgula." v07_datpri = '$this->v07_datpri' ";
       $virgula = ",";
       if(trim($this->v07_datpri) == null ){
         $this->erro_sql = " Campo data da primeira parcela nao Informado.";
         $this->erro_campo = "v07_datpri_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v07_datpri_dia"])){
         $sql  .= $virgula." v07_datpri = null ";
         $virgula = ",";
         if(trim($this->v07_datpri) == null ){
           $this->erro_sql = " Campo data da primeira parcela nao Informado.";
           $this->erro_campo = "v07_datpri_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v07_vlrmul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrmul"])){
       $sql  .= $virgula." v07_vlrmul = $this->v07_vlrmul ";
       $virgula = ",";
       if(trim($this->v07_vlrmul) == null ){
         $this->erro_sql = " Campo valor da multa nao Informado.";
         $this->erro_campo = "v07_vlrmul";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_vlrjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrjur"])){
       $sql  .= $virgula." v07_vlrjur = $this->v07_vlrjur ";
       $virgula = ",";
       if(trim($this->v07_vlrjur) == null ){
         $this->erro_sql = " Campo valor dos juros nao Informado.";
         $this->erro_campo = "v07_vlrjur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_perjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_perjur"])){
       $sql  .= $virgula." v07_perjur = $this->v07_perjur ";
       $virgula = ",";
       if(trim($this->v07_perjur) == null ){
         $this->erro_sql = " Campo percentual dos juros nao Informado.";
         $this->erro_campo = "v07_perjur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_permul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_permul"])){
       $sql  .= $virgula." v07_permul = $this->v07_permul ";
       $virgula = ",";
       if(trim($this->v07_permul) == null ){
         $this->erro_sql = " Campo percentual das multas nao Informado.";
         $this->erro_campo = "v07_permul";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_login"])){
       $sql  .= $virgula." v07_login = $this->v07_login ";
       $virgula = ",";
       if(trim($this->v07_login) == null ){
         $this->erro_sql = " Campo login nao Informado.";
         $this->erro_campo = "v07_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_mtermo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_mtermo"])){
       $sql  .= $virgula." v07_mtermo = $this->v07_mtermo ";
       $virgula = ",";
       if(trim($this->v07_mtermo) == null ){
         $this->erro_sql = " Campo termo nao Informado.";
         $this->erro_campo = "v07_mtermo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_numcgm"])){
       $sql  .= $virgula." v07_numcgm = $this->v07_numcgm ";
       $virgula = ",";
       if(trim($this->v07_numcgm) == null ){
         $this->erro_sql = " Campo Responsável pelo parcelamento nao Informado.";
         $this->erro_campo = "v07_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_hist"])){
       $sql  .= $virgula." v07_hist = '$this->v07_hist' ";
       $virgula = ",";
       if(trim($this->v07_hist) == null ){
         $this->erro_sql = " Campo historico nao Informado.";
         $this->erro_campo = "v07_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_ultpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_ultpar"])){
       $sql  .= $virgula." v07_ultpar = $this->v07_ultpar ";
       $virgula = ",";
       if(trim($this->v07_ultpar) == null ){
         $this->erro_sql = " Campo Valor da ultima parcela nao Informado.";
         $this->erro_campo = "v07_ultpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_desconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_desconto"])){
       $sql  .= $virgula." v07_desconto = $this->v07_desconto ";
       $virgula = ",";
       if(trim($this->v07_desconto) == null ){
         $this->erro_sql = " Campo Código do desconto nao Informado.";
         $this->erro_campo = "v07_desconto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_descjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_descjur"])){
       $sql  .= $virgula." v07_descjur = $this->v07_descjur ";
       $virgula = ",";
       if(trim($this->v07_descjur) == null ){
         $this->erro_sql = " Campo Desconto nos juros nao Informado.";
         $this->erro_campo = "v07_descjur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_descmul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_descmul"])){
       $sql  .= $virgula." v07_descmul = $this->v07_descmul ";
       $virgula = ",";
       if(trim($this->v07_descmul) == null ){
         $this->erro_sql = " Campo Desconto na multa nao Informado.";
         $this->erro_campo = "v07_descmul";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_situacao"])){
       $sql  .= $virgula." v07_situacao = $this->v07_situacao ";
       $virgula = ",";
       if(trim($this->v07_situacao) == null ){
         $this->erro_sql = " Campo Situacao nao Informado.";
         $this->erro_campo = "v07_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_instit"])){
       $sql  .= $virgula." v07_instit = $this->v07_instit ";
       $virgula = ",";
       if(trim($this->v07_instit) == null ){
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "v07_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_vlrhis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrhis"])){
       $sql  .= $virgula." v07_vlrhis = $this->v07_vlrhis ";
       $virgula = ",";
       if(trim($this->v07_vlrhis) == null ){
         $this->erro_sql = " Campo Valor Histórico nao Informado.";
         $this->erro_campo = "v07_vlrhis";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_vlrcor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrcor"])){
       $sql  .= $virgula." v07_vlrcor = $this->v07_vlrcor ";
       $virgula = ",";
       if(trim($this->v07_vlrcor) == null ){
         $this->erro_sql = " Campo Valor Corrigido nao Informado.";
         $this->erro_campo = "v07_vlrcor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_vlrdes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrdes"])){
       $sql  .= $virgula." v07_vlrdes = $this->v07_vlrdes ";
       $virgula = ",";
       if(trim($this->v07_vlrdes) == null ){
         $this->erro_sql = " Campo Valor Desconto nao Informado.";
         $this->erro_campo = "v07_vlrdes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v07_parcel!=null){
       $sql .= " v07_parcel = $this->v07_parcel";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v07_parcel));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,537,'$this->v07_parcel','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_parcel"]))
           $resac = db_query("insert into db_acount values($acount,103,537,'".AddSlashes(pg_result($resaco,$conresaco,'v07_parcel'))."','$this->v07_parcel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_dtlanc"]))
           $resac = db_query("insert into db_acount values($acount,103,538,'".AddSlashes(pg_result($resaco,$conresaco,'v07_dtlanc'))."','$this->v07_dtlanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_valor"]))
           $resac = db_query("insert into db_acount values($acount,103,539,'".AddSlashes(pg_result($resaco,$conresaco,'v07_valor'))."','$this->v07_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_numpre"]))
           $resac = db_query("insert into db_acount values($acount,103,540,'".AddSlashes(pg_result($resaco,$conresaco,'v07_numpre'))."','$this->v07_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_totpar"]))
           $resac = db_query("insert into db_acount values($acount,103,541,'".AddSlashes(pg_result($resaco,$conresaco,'v07_totpar'))."','$this->v07_totpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrpar"]))
           $resac = db_query("insert into db_acount values($acount,103,542,'".AddSlashes(pg_result($resaco,$conresaco,'v07_vlrpar'))."','$this->v07_vlrpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_dtvenc"]))
           $resac = db_query("insert into db_acount values($acount,103,543,'".AddSlashes(pg_result($resaco,$conresaco,'v07_dtvenc'))."','$this->v07_dtvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrent"]))
           $resac = db_query("insert into db_acount values($acount,103,544,'".AddSlashes(pg_result($resaco,$conresaco,'v07_vlrent'))."','$this->v07_vlrent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_datpri"]))
           $resac = db_query("insert into db_acount values($acount,103,545,'".AddSlashes(pg_result($resaco,$conresaco,'v07_datpri'))."','$this->v07_datpri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrmul"]))
           $resac = db_query("insert into db_acount values($acount,103,546,'".AddSlashes(pg_result($resaco,$conresaco,'v07_vlrmul'))."','$this->v07_vlrmul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrjur"]))
           $resac = db_query("insert into db_acount values($acount,103,547,'".AddSlashes(pg_result($resaco,$conresaco,'v07_vlrjur'))."','$this->v07_vlrjur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_perjur"]))
           $resac = db_query("insert into db_acount values($acount,103,548,'".AddSlashes(pg_result($resaco,$conresaco,'v07_perjur'))."','$this->v07_perjur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_permul"]))
           $resac = db_query("insert into db_acount values($acount,103,549,'".AddSlashes(pg_result($resaco,$conresaco,'v07_permul'))."','$this->v07_permul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_login"]))
           $resac = db_query("insert into db_acount values($acount,103,550,'".AddSlashes(pg_result($resaco,$conresaco,'v07_login'))."','$this->v07_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_mtermo"]))
           $resac = db_query("insert into db_acount values($acount,103,551,'".AddSlashes(pg_result($resaco,$conresaco,'v07_mtermo'))."','$this->v07_mtermo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,103,552,'".AddSlashes(pg_result($resaco,$conresaco,'v07_numcgm'))."','$this->v07_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_hist"]))
           $resac = db_query("insert into db_acount values($acount,103,553,'".AddSlashes(pg_result($resaco,$conresaco,'v07_hist'))."','$this->v07_hist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_ultpar"]))
           $resac = db_query("insert into db_acount values($acount,103,8641,'".AddSlashes(pg_result($resaco,$conresaco,'v07_ultpar'))."','$this->v07_ultpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_desconto"]))
           $resac = db_query("insert into db_acount values($acount,103,8642,'".AddSlashes(pg_result($resaco,$conresaco,'v07_desconto'))."','$this->v07_desconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_descjur"]))
           $resac = db_query("insert into db_acount values($acount,103,8643,'".AddSlashes(pg_result($resaco,$conresaco,'v07_descjur'))."','$this->v07_descjur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_descmul"]))
           $resac = db_query("insert into db_acount values($acount,103,8644,'".AddSlashes(pg_result($resaco,$conresaco,'v07_descmul'))."','$this->v07_descmul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_situacao"]))
           $resac = db_query("insert into db_acount values($acount,103,9552,'".AddSlashes(pg_result($resaco,$conresaco,'v07_situacao'))."','$this->v07_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_instit"]))
           $resac = db_query("insert into db_acount values($acount,103,10577,'".AddSlashes(pg_result($resaco,$conresaco,'v07_instit'))."','$this->v07_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrhis"]))
           $resac = db_query("insert into db_acount values($acount,103,10786,'".AddSlashes(pg_result($resaco,$conresaco,'v07_vlrhis'))."','$this->v07_vlrhis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrcor"]))
           $resac = db_query("insert into db_acount values($acount,103,10787,'".AddSlashes(pg_result($resaco,$conresaco,'v07_vlrcor'))."','$this->v07_vlrcor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrdes"]))
           $resac = db_query("insert into db_acount values($acount,103,10788,'".AddSlashes(pg_result($resaco,$conresaco,'v07_vlrdes'))."','$this->v07_vlrdes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v07_parcel;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v07_parcel;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v07_parcel;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($v07_parcel=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v07_parcel));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,537,'$v07_parcel','E')");
         $resac = db_query("insert into db_acount values($acount,103,537,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_parcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,538,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,539,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,540,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,541,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_totpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,542,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_vlrpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,543,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,544,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_vlrent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,545,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_datpri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,546,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_vlrmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,547,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_vlrjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,548,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_perjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,549,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_permul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,550,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,551,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_mtermo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,552,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,553,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,8641,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_ultpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,8642,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,8643,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_descjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,8644,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_descmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,9552,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,10577,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,10786,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_vlrhis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,10787,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,103,10788,'','".AddSlashes(pg_result($resaco,$iresaco,'v07_vlrdes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from termo
                    where ";
        $sql2 = "";
        if($dbwhere==null || $dbwhere ==""){
            if($v07_parcel != ""){
                if($sql2!=""){
                    $sql2 .= " and ";
                }
                $sql2 .= " v07_parcel = $v07_parcel ";
            }
        }else{
            $sql2 = $dbwhere;
        }
        $result = db_query($sql.$sql2);
        if($result==false){
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : ".$v07_parcel;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        }else{
            if(pg_affected_rows($result)==0){
                $this->erro_banco = "";
                $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : ".$v07_parcel;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            }else{
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : ".$v07_parcel;
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
            $this->erro_sql   = "Record Vazio na Tabela:termo";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    function sql_query ( $v07_parcel=null,$campos="*",$ordem=null,$dbwhere=""){
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
        $sql .= " from termo ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = termo.v07_numcgm";
        $sql .= "      inner join db_config  on  db_config.codigo = termo.v07_instit";
//     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
        $sql2 = "";
        if($dbwhere==""){
            if($v07_parcel!=null ){
                $sql2 .= " where termo.v07_parcel = $v07_parcel ";
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
    function sql_query_arre ( $v07_parcel=null,$campos="*",$ordem=null,$dbwhere=""){
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
        $sql .= " from termo ";
        $sql .= "      inner join cgm on  cgm.z01_numcgm = termo.v07_numcgm";
        $sql .= "      inner join arrecad on  arrecad.k00_numpre = termo.v07_numpre";
        $sql2 = "";
        if($dbwhere==""){
            if($v07_parcel!=null ){
                $sql2 .= " where termo.v07_parcel = $v07_parcel ";
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
    function sql_query_consulta ( $v07_parcel=null,$campos="*",$ordem=null,$dbwhere=""){
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
        $sql .= " from termo ";
        $sql .= "      inner join cgm        on cgm.z01_numcgm        = termo.v07_numcgm      ";
        $sql .= "      inner join db_config  on db_config.codigo      = termo.v07_instit      ";
        $sql .= "      inner join arrenumcgm on arrenumcgm.k00_numpre = termo.v07_numpre      ";
        $sql .= "      inner join cgm resp   on resp.z01_numcgm       = arrenumcgm.k00_numcgm ";
        $sql2 = "";
        if($dbwhere==""){
            if($v07_parcel!=null ){
                $sql2 .= " where termo.v07_parcel = $v07_parcel ";
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
    function sql_query_file ( $v07_parcel=null,$campos="*",$ordem=null,$dbwhere=""){
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
        $sql .= " from termo ";
        $sql2 = "";
        if($dbwhere==""){
            if($v07_parcel!=null ){
                $sql2 .= " where termo.v07_parcel = $v07_parcel ";
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
    function sql_query_inf ( $v07_parcel=null,$campos="*",$ordem=null,$dbwhere=""){
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
        $sql .= " from (select ";
        $sql .= "distinct v07_parcel,
           		      v07_dtlanc,
           		      case when k00_inscr is not null then 'Inscrição'  else
                        (case when k00_matric is not null then 'Matrícula' else
                          (case when arrenumcgm.k00_numcgm is not null then 'Cgm' 
		 				  end )
                        end )
                      end as dl_Tipo,
                      case when k00_inscr is not null then k00_inscr  else
                        (case when k00_matric is not null then k00_matric else
                          (case when arrenumcgm.k00_numcgm is not null then arrenumcgm.k00_numcgm  
		 				  end )
                        end )
                      end as dl_Cod,z01_nome as db_z01_nome";
        $sql .= " from termo ";
        $sql .= "      inner join arrecad on arrecad.k00_numpre = termo.v07_numpre";
        $sql .= "      inner join cgm on  cgm.z01_numcgm = arrecad.k00_numcgm";
        $sql .= "      left  join arrenumcgm on  arrenumcgm.k00_numpre= termo.v07_numpre";
        $sql .= "      left  join arrematric on  arrematric.k00_numpre= termo.v07_numpre";
        $sql .= "      left  join arreinscr on  arreinscr.k00_numpre= termo.v07_numpre";
        $sql .= "      ) as x  ";
        $sql2 = "";
        if($dbwhere==""){
            if($v07_parcel!=null ){
                $sql2 .= " where termo.v07_parcel = $v07_parcel ";
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

    function sql_query_origem_divida($numpre=null, $campos="*", $lPrimeiroParcelamento = false){

        if($numpre == null){
            return null;
        }

        if ($lPrimeiroParcelamento){
            $sSqlPrimeiroParcelamento = "select rinumpre as primeiro_numpre from fc_parc_origem_completo({$numpre}) order by rdtlanc limit 1";
            $rsPrimeiroParcelamento   = db_query($sSqlPrimeiroParcelamento);
            if ($rsPrimeiroParcelamento && pg_num_rows($rsPrimeiroParcelamento) > 0) {
                $oPrimeiroNumpre = db_utils::fieldsMemory($rsPrimeiroParcelamento,0);
                $numpre = $oPrimeiroNumpre->primeiro_numpre;
            }else{
                throw (new Exception("Origem do parcelamento de numpre : [{$numpre}] não encontrado"));
            }
        }

        $sql = "select $campos
	            from  fc_parc_origem_completo({$numpre})as origemparcelamento 
             inner join termo       on termo.v07_parcel      = riparcel                
             inner join termodiv    on termodiv.parcel       = riparcel                
             inner join divida      on divida.v01_coddiv     = termodiv.coddiv         
                                   and v01_instit            = " . db_getsession("DB_instit"). "
             inner join proced      on proced.v03_codigo     = divida.v01_proced
             inner join arrenumcgm  on arrenumcgm.k00_numpre = rinumpre
              left join arrematric  on arrematric.k00_numpre = rinumpre
              left join iptubase    on iptubase.j01_matric   = arrematric.k00_matric   
              left join lote        on lote.j34_idbql        = iptubase.j01_idbql                    
              left join arreinscr   on arreinscr.k00_numpre  = rinumpre
             union                                                                 
            select $campos                               
              from fc_parc_origem_completo({$numpre}) as origemparcelamento 
             inner join termo       on termo.v07_parcel      = riparcel         
  					 inner join termoini    on termoini.parcel       = riparcel         
						 inner join inicialcert on inicial               = v51_inicial      
             inner join certdiv     on v14_certid            = v51_certidao     
						 inner join divida      on divida.v01_coddiv     = v14_coddiv       
                                   and divida.v01_instit     = " . db_getsession("DB_instit") . " 
             inner join proced      on proced.v03_codigo     = divida.v01_proced  
             inner join arrenumcgm  on arrenumcgm.k00_numpre = rinumpre
              left join arrematric  on arrematric.k00_numpre = rinumpre
						  left join iptubase    on iptubase.j01_matric   = arrematric.k00_matric   
						  left join lote        on lote.j34_idbql        = iptubase.j01_idbql                 
              left join arreinscr   on arreinscr.k00_numpre  = rinumpre  ";

        return $sql;

    }

    function sql_query_acerta_parcelamento($parcelamento, $valor_entrada, $valor_parcela, $tipo_parcelamento = 22) {

        $sql = "
				    select d.parcelamento,
				       d.numpre,
				       d.numpar,
				       d.valor_total_parcelamento,
				       d.receita,
				       d.qtde, 
				       round(d.novo_valor_receita,2) as novo_valor_receita,
				       round(d.novo_valor_primeira_parcela,2) as novo_valor_primeira_parcela,
				       round(d.novo_valor_outras_parcela,2) as novo_valor_outras_parcelas, 
				       round(d.novo_valor_ultima_parcela,2) as novo_valor_ultima_parcela,
				       round(d.nova_entrada_termo,2) as nova_entrada_termo,
				       round(d.nova_parcela_termo,2) as nova_parcela_termo,
				       round(d.valor_total_parcelamento - (d.nova_entrada_termo + (d.nova_parcela_termo * (d.numpar - 2))),2) as novo_valor_ultima_parcela_termo
				  from (
				select c.parcelamento,
				       c.numpre,
				       c.numpar,
				       c.valor_total_parcelamento,
				       c.receita,
				       c.qtde, 
				       c.novo_valor_receita,
				       c.novo_valor_primeira_parcela,
				       c.novo_valor_outras_parcela, 
				       c.novo_valor_receita - (c.novo_valor_primeira_parcela + (c.novo_valor_outras_parcela * (c.numpar - 2))) as novo_valor_ultima_parcela,
				       (c.novo_valor_primeira_parcela * c.qtde) as nova_entrada_termo,
				       (c.novo_valor_outras_parcela * c.qtde) as nova_parcela_termo,
				       (c.novo_valor_receita - (c.novo_valor_primeira_parcela + (c.novo_valor_outras_parcela * (c.numpar - 2)))) * c.qtde as novo_valor_ultima_parcela_termo
				  from(
				select b.parcelamento,
				       b.numpre,
				       b.numpar,
				       b.valor_total_parcelamento,
				       b.receita,
				       b.qtde, 
				       b.valor_total_parcelamento / b.qtde as novo_valor_receita, 
				       ({$valor_entrada} / b.qtde) as novo_valor_primeira_parcela,
				       ({$valor_parcela} / b.qtde) as novo_valor_outras_parcela
				from( 
				select a.parcelamento,
				       a.numpre,
				       a.numpar,
				       a.valor_total_parcelamento,
				       a.receita,
				      (select count(distinct k00_receit) from arrecad where k00_numpre = a.numpre) as qtde
				  from (
				  select v07_parcel     as parcelamento, 
				         v07_numpre     as numpre, 
				         v07_totpar     as numpar,
				         v07_valor      as valor_total_parcelamento, 
				         k00_receit     as receita
				    from termo
				   inner join arrecad on k00_numpre = v07_numpre
				   where v07_parcel   = {$parcelamento}
				     and v07_desconto = {$tipo_parcelamento}
				   group by v07_parcel, v07_numpre, v07_totpar, v07_valor, k00_receit
				   order by k00_receit) as a) as b) as c) as d ";

        return $sql;

    }

    function sql_query_parcelas_termo($parcelamento, $tipo_parcelamento = 22) {

        $sql = "select v07_parcel, v07_totpar, k00_numpre, k00_numpar, sum(k00_valor) as k00_valor
              from termo
             inner join arrecad on k00_numpre = v07_numpre
             where v07_parcel   = {$parcelamento}
               and v07_desconto = {$tipo_parcelamento}
             group by v07_parcel, v07_totpar, k00_numpre, k00_numpar
             order by v07_parcel, k00_numpre, k00_numpar";

        return $sql;
    }

    /**
     * Função que cria a query para executar a simulação da anulação de parcelamento
     * @param  integer $iTermo
     * @return string  Query montada
     */
    public function sql_query_simular_anulacao($iTermo)
    {
        $sSql = "select fc_parc_gera_simulacao_anulacao({$iTermo}) as simulacao";
        return $sSql;
    }

    /**
     * Função que cria a query para executar a anulação do parcelamento
     * @param integer  $iCodigoSimulacao
     * @param integer  $iUsuario
     * @return string  Query montada
     */
    public function sql_query_anular_parcelamento($iCodigoSimulacao, $iUsuario)
    {
        $sSql = "select fc_excluiparcelamento({$iCodigoSimulacao}, {$iUsuario}, null, null) as anulacao";
        return $sSql;
    }

    public function sql_query_simulacao($iTermo = null, $iSumulacao = null, $sCampos = "*", $sOrderBy = null)
    {
        $sWhere = "";
        $sAnd   = "where";

        if (!is_null($iTermo)) {

            $sWhere = " {$sAnd} v21_parcel = {$iTermo}";
            $sAnd   = "and";
        }

        if ( !is_null($iSumulacao) ) {
            $sWhere = " {$sAnd} v21_sequencial = {$iTermo}";
        }

        $sSql  = " select {$sCampos}  ";
        $sSql .= "   from termosimula ";
        $sSql .= $sWhere;

        if ( !is_null($sOrderBy) ) {
            $sSql .= "  order by v21_sequencial ";
        }

        return $sSql;
    }

    /**
     * Função que cria o sql para consultar a origem do parcelamento
     * @param  integer $iParcelamento
     * @return string  query pronta
     * @throws DBException
     */
    public function sql_query_origem_parcelamento($iParcelamento)
    {
        $sSqlTipoParcelamento = "select fc_parc_gettipoparcelamento({$iParcelamento}) as tipo_parcelamento";
        $rsTipoParcelamento   = db_query($sSqlTipoParcelamento);

        if (!$rsTipoParcelamento) {
            throw new \DBException("Erro ao buscar tipo de origem do parcelamento");
        }

        if (pg_num_rows($rsTipoParcelamento) == 0) {
            throw new \DBException("Tipo de parcelamento não encontrado.");
        }

        $oTipoParcelamento = \db_utils::fieldsMemory($rsTipoParcelamento, 0);

        /**
         * Códigos de acordo com a pl fc_excluiparcelamento
         */
        $aTiposParcelamento = array(
          'termoreparc'  => 2,
          'termodiv'     => 1,
          'termoini'     => 3,
          'termodiver'   => 4,
          'termocontrib' => 5);

        $iTipo = $aTiposParcelamento[$oTipoParcelamento->tipo_parcelamento];

        $sSqlOrigem = "select fc_parc_getselectorigens($iParcelamento, $iTipo) as sql_origem";
        $rsOrigem   = db_query($sSqlOrigem);

        if ( !$rsOrigem || pg_num_rows($rsOrigem) == 0) {
            throw new \DBException("Erro ao criar consulta da origem do parcelamento.");
        }

        $sSqlOrigemNucleo = \db_utils::fieldsMemory($rsOrigem, 0)->sql_origem;

        return $sSqlOrigemNucleo;
    }
}
?>