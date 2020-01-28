<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: compras
//CLASSE DA ENTIDADE pccontratos
class cl_pccontratos { 
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
   var $p71_codcontr = 0; 
   var $p71_datalanc_dia = null; 
   var $p71_datalanc_mes = null; 
   var $p71_datalanc_ano = null; 
   var $p71_datalanc = null; 
   var $p71_numcgm = 0; 
   var $p71_codtipo = 0; 
   var $p71_dtini_dia = null; 
   var $p71_dtini_mes = null; 
   var $p71_dtini_ano = null; 
   var $p71_dtini = null; 
   var $p71_dtfim_dia = null; 
   var $p71_dtfim_mes = null; 
   var $p71_dtfim_ano = null; 
   var $p71_dtfim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p71_codcontr = int4 = Código do contrato 
                 p71_datalanc = date = Data de lançamento 
                 p71_numcgm = int4 = Numcgm 
                 p71_codtipo = int4 = Código do tipo de contrato 
                 p71_dtini = date = Data inicial do contrato 
                 p71_dtfim = date = Data final do contrato 
                 ";
   //funcao construtor da classe 
   function cl_pccontratos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pccontratos"); 
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
       $this->p71_codcontr = ($this->p71_codcontr == ""?@$GLOBALS["HTTP_POST_VARS"]["p71_codcontr"]:$this->p71_codcontr);
       if($this->p71_datalanc == ""){
         $this->p71_datalanc_dia = ($this->p71_datalanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p71_datalanc_dia"]:$this->p71_datalanc_dia);
         $this->p71_datalanc_mes = ($this->p71_datalanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p71_datalanc_mes"]:$this->p71_datalanc_mes);
         $this->p71_datalanc_ano = ($this->p71_datalanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p71_datalanc_ano"]:$this->p71_datalanc_ano);
         if($this->p71_datalanc_dia != ""){
            $this->p71_datalanc = $this->p71_datalanc_ano."-".$this->p71_datalanc_mes."-".$this->p71_datalanc_dia;
         }
       }
       $this->p71_numcgm = ($this->p71_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["p71_numcgm"]:$this->p71_numcgm);
       $this->p71_codtipo = ($this->p71_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["p71_codtipo"]:$this->p71_codtipo);
       if($this->p71_dtini == ""){
         $this->p71_dtini_dia = ($this->p71_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p71_dtini_dia"]:$this->p71_dtini_dia);
         $this->p71_dtini_mes = ($this->p71_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p71_dtini_mes"]:$this->p71_dtini_mes);
         $this->p71_dtini_ano = ($this->p71_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p71_dtini_ano"]:$this->p71_dtini_ano);
         if($this->p71_dtini_dia != ""){
            $this->p71_dtini = $this->p71_dtini_ano."-".$this->p71_dtini_mes."-".$this->p71_dtini_dia;
         }
       }
       if($this->p71_dtfim == ""){
         $this->p71_dtfim_dia = ($this->p71_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p71_dtfim_dia"]:$this->p71_dtfim_dia);
         $this->p71_dtfim_mes = ($this->p71_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p71_dtfim_mes"]:$this->p71_dtfim_mes);
         $this->p71_dtfim_ano = ($this->p71_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p71_dtfim_ano"]:$this->p71_dtfim_ano);
         if($this->p71_dtfim_dia != ""){
            $this->p71_dtfim = $this->p71_dtfim_ano."-".$this->p71_dtfim_mes."-".$this->p71_dtfim_dia;
         }
       }
     }else{
       $this->p71_codcontr = ($this->p71_codcontr == ""?@$GLOBALS["HTTP_POST_VARS"]["p71_codcontr"]:$this->p71_codcontr);
     }
   }
   // funcao para inclusao
   function incluir ($p71_codcontr){ 
      $this->atualizacampos();
     if($this->p71_datalanc == null ){ 
       $this->erro_sql = " Campo Data de lançamento nao Informado.";
       $this->erro_campo = "p71_datalanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p71_numcgm == null ){ 
       $this->erro_sql = " Campo Numcgm nao Informado.";
       $this->erro_campo = "p71_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p71_codtipo == null ){ 
       $this->erro_sql = " Campo Código do tipo de contrato nao Informado.";
       $this->erro_campo = "p71_codtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p71_dtini == null ){ 
       $this->erro_sql = " Campo Data inicial do contrato nao Informado.";
       $this->erro_campo = "p71_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p71_dtfim == null ){ 
       $this->erro_sql = " Campo Data final do contrato nao Informado.";
       $this->erro_campo = "p71_dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($p71_codcontr == "" || $p71_codcontr == null ){
       $result = db_query("select nextval('pccontratos_p71_codcontr_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pccontratos_p71_codcontr_seq do campo: p71_codcontr"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->p71_codcontr = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pccontratos_p71_codcontr_seq");
       if(($result != false) && (pg_result($result,0,0) < $p71_codcontr)){
         $this->erro_sql = " Campo p71_codcontr maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p71_codcontr = $p71_codcontr; 
       }
     }
     if(($this->p71_codcontr == null) || ($this->p71_codcontr == "") ){ 
       $this->erro_sql = " Campo p71_codcontr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pccontratos(
                                       p71_codcontr 
                                      ,p71_datalanc 
                                      ,p71_numcgm 
                                      ,p71_codtipo 
                                      ,p71_dtini 
                                      ,p71_dtfim 
                       )
                values (
                                $this->p71_codcontr 
                               ,".($this->p71_datalanc == "null" || $this->p71_datalanc == ""?"null":"'".$this->p71_datalanc."'")." 
                               ,$this->p71_numcgm 
                               ,$this->p71_codtipo 
                               ,".($this->p71_dtini == "null" || $this->p71_dtini == ""?"null":"'".$this->p71_dtini."'")." 
                               ,".($this->p71_dtfim == "null" || $this->p71_dtfim == ""?"null":"'".$this->p71_dtfim."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "contratos ($this->p71_codcontr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "contratos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "contratos ($this->p71_codcontr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p71_codcontr;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->p71_codcontr));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6112,'$this->p71_codcontr','I')");
       $resac = db_query("insert into db_acount values($acount,983,6112,'','".AddSlashes(pg_result($resaco,0,'p71_codcontr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,983,6113,'','".AddSlashes(pg_result($resaco,0,'p71_datalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,983,6114,'','".AddSlashes(pg_result($resaco,0,'p71_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,983,6115,'','".AddSlashes(pg_result($resaco,0,'p71_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,983,6116,'','".AddSlashes(pg_result($resaco,0,'p71_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,983,6117,'','".AddSlashes(pg_result($resaco,0,'p71_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p71_codcontr=null) { 
      $this->atualizacampos();
     $sql = " update pccontratos set ";
     $virgula = "";
     if(trim($this->p71_codcontr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p71_codcontr"])){ 
       $sql  .= $virgula." p71_codcontr = $this->p71_codcontr ";
       $virgula = ",";
       if(trim($this->p71_codcontr) == null ){ 
         $this->erro_sql = " Campo Código do contrato nao Informado.";
         $this->erro_campo = "p71_codcontr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p71_datalanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p71_datalanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p71_datalanc_dia"] !="") ){ 
       $sql  .= $virgula." p71_datalanc = '$this->p71_datalanc' ";
       $virgula = ",";
       if(trim($this->p71_datalanc) == null ){ 
         $this->erro_sql = " Campo Data de lançamento nao Informado.";
         $this->erro_campo = "p71_datalanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["p71_datalanc_dia"])){ 
         $sql  .= $virgula." p71_datalanc = null ";
         $virgula = ",";
         if(trim($this->p71_datalanc) == null ){ 
           $this->erro_sql = " Campo Data de lançamento nao Informado.";
           $this->erro_campo = "p71_datalanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->p71_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p71_numcgm"])){ 
       $sql  .= $virgula." p71_numcgm = $this->p71_numcgm ";
       $virgula = ",";
       if(trim($this->p71_numcgm) == null ){ 
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "p71_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p71_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p71_codtipo"])){ 
       $sql  .= $virgula." p71_codtipo = $this->p71_codtipo ";
       $virgula = ",";
       if(trim($this->p71_codtipo) == null ){ 
         $this->erro_sql = " Campo Código do tipo de contrato nao Informado.";
         $this->erro_campo = "p71_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p71_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p71_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p71_dtini_dia"] !="") ){ 
       $sql  .= $virgula." p71_dtini = '$this->p71_dtini' ";
       $virgula = ",";
       if(trim($this->p71_dtini) == null ){ 
         $this->erro_sql = " Campo Data inicial do contrato nao Informado.";
         $this->erro_campo = "p71_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["p71_dtini_dia"])){ 
         $sql  .= $virgula." p71_dtini = null ";
         $virgula = ",";
         if(trim($this->p71_dtini) == null ){ 
           $this->erro_sql = " Campo Data inicial do contrato nao Informado.";
           $this->erro_campo = "p71_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->p71_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p71_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p71_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." p71_dtfim = '$this->p71_dtfim' ";
       $virgula = ",";
       if(trim($this->p71_dtfim) == null ){ 
         $this->erro_sql = " Campo Data final do contrato nao Informado.";
         $this->erro_campo = "p71_dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["p71_dtfim_dia"])){ 
         $sql  .= $virgula." p71_dtfim = null ";
         $virgula = ",";
         if(trim($this->p71_dtfim) == null ){ 
           $this->erro_sql = " Campo Data final do contrato nao Informado.";
           $this->erro_campo = "p71_dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($p71_codcontr!=null){
       $sql .= " p71_codcontr = $this->p71_codcontr";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->p71_codcontr));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6112,'$this->p71_codcontr','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p71_codcontr"]))
           $resac = db_query("insert into db_acount values($acount,983,6112,'".AddSlashes(pg_result($resaco,$conresaco,'p71_codcontr'))."','$this->p71_codcontr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p71_datalanc"]))
           $resac = db_query("insert into db_acount values($acount,983,6113,'".AddSlashes(pg_result($resaco,$conresaco,'p71_datalanc'))."','$this->p71_datalanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p71_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,983,6114,'".AddSlashes(pg_result($resaco,$conresaco,'p71_numcgm'))."','$this->p71_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p71_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,983,6115,'".AddSlashes(pg_result($resaco,$conresaco,'p71_codtipo'))."','$this->p71_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p71_dtini"]))
           $resac = db_query("insert into db_acount values($acount,983,6116,'".AddSlashes(pg_result($resaco,$conresaco,'p71_dtini'))."','$this->p71_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p71_dtfim"]))
           $resac = db_query("insert into db_acount values($acount,983,6117,'".AddSlashes(pg_result($resaco,$conresaco,'p71_dtfim'))."','$this->p71_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "contratos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p71_codcontr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "contratos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p71_codcontr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p71_codcontr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p71_codcontr=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($p71_codcontr));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6112,'$p71_codcontr','E')");
         $resac = db_query("insert into db_acount values($acount,983,6112,'','".AddSlashes(pg_result($resaco,$iresaco,'p71_codcontr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,983,6113,'','".AddSlashes(pg_result($resaco,$iresaco,'p71_datalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,983,6114,'','".AddSlashes(pg_result($resaco,$iresaco,'p71_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,983,6115,'','".AddSlashes(pg_result($resaco,$iresaco,'p71_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,983,6116,'','".AddSlashes(pg_result($resaco,$iresaco,'p71_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,983,6117,'','".AddSlashes(pg_result($resaco,$iresaco,'p71_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pccontratos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p71_codcontr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p71_codcontr = $p71_codcontr ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "contratos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p71_codcontr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "contratos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p71_codcontr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p71_codcontr;
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
        $this->erro_sql   = "Record Vazio na Tabela:pccontratos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $p71_codcontr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pccontratos ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pccontratos.p71_numcgm";
     $sql .= "      inner join pctipocontrato  on  pctipocontrato.p70_codtipo = pccontratos.p71_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($p71_codcontr!=null ){
         $sql2 .= " where pccontratos.p71_codcontr = $p71_codcontr "; 
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
   function sql_query_file ( $p71_codcontr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pccontratos ";
     $sql2 = "";
     if($dbwhere==""){
       if($p71_codcontr!=null ){
         $sql2 .= " where pccontratos.p71_codcontr = $p71_codcontr "; 
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
}
?>