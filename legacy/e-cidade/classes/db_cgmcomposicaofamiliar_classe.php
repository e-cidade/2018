<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: protocolo
//CLASSE DA ENTIDADE cgmcomposicaofamiliar
class cl_cgmcomposicaofamiliar { 
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
   var $z15_sequencial = 0; 
   var $z15_cgmfamilia = 0; 
   var $z15_cgmtipofamiliar = 0; 
   var $z15_numcgm = 0; 
   var $z15_datainicial = 0; 
   var $z15_datafinal_dia = null; 
   var $z15_datafinal_mes = null; 
   var $z15_datafinal_ano = null; 
   var $z15_datafinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 z15_sequencial = int4 = Sequencial 
                 z15_cgmfamilia = int4 = Familia 
                 z15_cgmtipofamiliar = int4 = Tipo de Familiar 
                 z15_numcgm = int4 = Cgm 
                 z15_datainicial = int4 = Data Inicial 
                 z15_datafinal = date = Data Final 
                 ";
   //funcao construtor da classe 
   function cl_cgmcomposicaofamiliar() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cgmcomposicaofamiliar"); 
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
       $this->z15_sequencial = ($this->z15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["z15_sequencial"]:$this->z15_sequencial);
       $this->z15_cgmfamilia = ($this->z15_cgmfamilia == ""?@$GLOBALS["HTTP_POST_VARS"]["z15_cgmfamilia"]:$this->z15_cgmfamilia);
       $this->z15_cgmtipofamiliar = ($this->z15_cgmtipofamiliar == ""?@$GLOBALS["HTTP_POST_VARS"]["z15_cgmtipofamiliar"]:$this->z15_cgmtipofamiliar);
       $this->z15_numcgm = ($this->z15_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["z15_numcgm"]:$this->z15_numcgm);
       $this->z15_datainicial = ($this->z15_datainicial == ""?@$GLOBALS["HTTP_POST_VARS"]["z15_datainicial"]:$this->z15_datainicial);
       if($this->z15_datafinal == ""){
         $this->z15_datafinal_dia = ($this->z15_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z15_datafinal_dia"]:$this->z15_datafinal_dia);
         $this->z15_datafinal_mes = ($this->z15_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z15_datafinal_mes"]:$this->z15_datafinal_mes);
         $this->z15_datafinal_ano = ($this->z15_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z15_datafinal_ano"]:$this->z15_datafinal_ano);
         if($this->z15_datafinal_dia != ""){
            $this->z15_datafinal = $this->z15_datafinal_ano."-".$this->z15_datafinal_mes."-".$this->z15_datafinal_dia;
         }
       }
     }else{
       $this->z15_sequencial = ($this->z15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["z15_sequencial"]:$this->z15_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($z15_sequencial){ 
      $this->atualizacampos();
     if($this->z15_cgmfamilia == null ){ 
       $this->erro_sql = " Campo Familia nao Informado.";
       $this->erro_campo = "z15_cgmfamilia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z15_cgmtipofamiliar == null ){ 
       $this->erro_sql = " Campo Tipo de Familiar nao Informado.";
       $this->erro_campo = "z15_cgmtipofamiliar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z15_numcgm == null ){ 
       $this->erro_sql = " Campo Cgm nao Informado.";
       $this->erro_campo = "z15_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z15_datainicial == null ){ 
       $this->z15_datainicial = "null";
     }
     if($this->z15_datafinal == null ){ 
       $this->z15_datafinal = "null";
     }
     if($z15_sequencial == "" || $z15_sequencial == null ){
       $result = db_query("select nextval('cgmcomposicaofamiliar_z15_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cgmcomposicaofamiliar_z15_sequencial_seq do campo: z15_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->z15_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cgmcomposicaofamiliar_z15_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $z15_sequencial)){
         $this->erro_sql = " Campo z15_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->z15_sequencial = $z15_sequencial; 
       }
     }
     if(($this->z15_sequencial == null) || ($this->z15_sequencial == "") ){ 
       $this->erro_sql = " Campo z15_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cgmcomposicaofamiliar(
                                       z15_sequencial 
                                      ,z15_cgmfamilia 
                                      ,z15_cgmtipofamiliar 
                                      ,z15_numcgm 
                                      ,z15_datainicial 
                                      ,z15_datafinal 
                       )
                values (
                                $this->z15_sequencial 
                               ,$this->z15_cgmfamilia 
                               ,$this->z15_cgmtipofamiliar 
                               ,$this->z15_numcgm 
                               ,$this->z15_datainicial 
                               ,".($this->z15_datafinal == "null" || $this->z15_datafinal == ""?"null":"'".$this->z15_datafinal."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Composição Familiar do CGM ($this->z15_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Composição Familiar do CGM já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Composição Familiar do CGM ($this->z15_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z15_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->z15_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17040,'$this->z15_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3012,17040,'','".AddSlashes(pg_result($resaco,0,'z15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3012,17041,'','".AddSlashes(pg_result($resaco,0,'z15_cgmfamilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3012,17042,'','".AddSlashes(pg_result($resaco,0,'z15_cgmtipofamiliar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3012,17043,'','".AddSlashes(pg_result($resaco,0,'z15_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3012,17044,'','".AddSlashes(pg_result($resaco,0,'z15_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3012,17045,'','".AddSlashes(pg_result($resaco,0,'z15_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($z15_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cgmcomposicaofamiliar set ";
     $virgula = "";
     if(trim($this->z15_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z15_sequencial"])){ 
       $sql  .= $virgula." z15_sequencial = $this->z15_sequencial ";
       $virgula = ",";
       if(trim($this->z15_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "z15_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z15_cgmfamilia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z15_cgmfamilia"])){ 
       $sql  .= $virgula." z15_cgmfamilia = $this->z15_cgmfamilia ";
       $virgula = ",";
       if(trim($this->z15_cgmfamilia) == null ){ 
         $this->erro_sql = " Campo Familia nao Informado.";
         $this->erro_campo = "z15_cgmfamilia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z15_cgmtipofamiliar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z15_cgmtipofamiliar"])){ 
       $sql  .= $virgula." z15_cgmtipofamiliar = $this->z15_cgmtipofamiliar ";
       $virgula = ",";
       if(trim($this->z15_cgmtipofamiliar) == null ){ 
         $this->erro_sql = " Campo Tipo de Familiar nao Informado.";
         $this->erro_campo = "z15_cgmtipofamiliar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z15_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z15_numcgm"])){ 
       $sql  .= $virgula." z15_numcgm = $this->z15_numcgm ";
       $virgula = ",";
       if(trim($this->z15_numcgm) == null ){ 
         $this->erro_sql = " Campo Cgm nao Informado.";
         $this->erro_campo = "z15_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z15_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z15_datainicial"])){ 
        if(trim($this->z15_datainicial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z15_datainicial"])){ 
           $this->z15_datainicial = "0" ; 
        } 
       $sql  .= $virgula." z15_datainicial = $this->z15_datainicial ";
       $virgula = ",";
     }
     if(trim($this->z15_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z15_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z15_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." z15_datafinal = '$this->z15_datafinal' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z15_datafinal_dia"])){ 
         $sql  .= $virgula." z15_datafinal = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($z15_sequencial!=null){
       $sql .= " z15_sequencial = $this->z15_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->z15_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17040,'$this->z15_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z15_sequencial"]) || $this->z15_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3012,17040,'".AddSlashes(pg_result($resaco,$conresaco,'z15_sequencial'))."','$this->z15_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z15_cgmfamilia"]) || $this->z15_cgmfamilia != "")
           $resac = db_query("insert into db_acount values($acount,3012,17041,'".AddSlashes(pg_result($resaco,$conresaco,'z15_cgmfamilia'))."','$this->z15_cgmfamilia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z15_cgmtipofamiliar"]) || $this->z15_cgmtipofamiliar != "")
           $resac = db_query("insert into db_acount values($acount,3012,17042,'".AddSlashes(pg_result($resaco,$conresaco,'z15_cgmtipofamiliar'))."','$this->z15_cgmtipofamiliar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z15_numcgm"]) || $this->z15_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,3012,17043,'".AddSlashes(pg_result($resaco,$conresaco,'z15_numcgm'))."','$this->z15_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z15_datainicial"]) || $this->z15_datainicial != "")
           $resac = db_query("insert into db_acount values($acount,3012,17044,'".AddSlashes(pg_result($resaco,$conresaco,'z15_datainicial'))."','$this->z15_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z15_datafinal"]) || $this->z15_datafinal != "")
           $resac = db_query("insert into db_acount values($acount,3012,17045,'".AddSlashes(pg_result($resaco,$conresaco,'z15_datafinal'))."','$this->z15_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Composição Familiar do CGM nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->z15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Composição Familiar do CGM nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->z15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($z15_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($z15_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17040,'$z15_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3012,17040,'','".AddSlashes(pg_result($resaco,$iresaco,'z15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3012,17041,'','".AddSlashes(pg_result($resaco,$iresaco,'z15_cgmfamilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3012,17042,'','".AddSlashes(pg_result($resaco,$iresaco,'z15_cgmtipofamiliar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3012,17043,'','".AddSlashes(pg_result($resaco,$iresaco,'z15_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3012,17044,'','".AddSlashes(pg_result($resaco,$iresaco,'z15_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3012,17045,'','".AddSlashes(pg_result($resaco,$iresaco,'z15_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cgmcomposicaofamiliar
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($z15_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " z15_sequencial = $z15_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Composição Familiar do CGM nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$z15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Composição Familiar do CGM nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$z15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$z15_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cgmcomposicaofamiliar";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $z15_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cgmcomposicaofamiliar ";
     $sql .= "      inner join cgm          on cgm.z01_numcgm              = cgmcomposicaofamiliar.z15_numcgm";
     $sql .= "      inner join cgmfamilia   on cgmfamilia.z13_sequencial   = cgmcomposicaofamiliar.z15_cgmfamilia";
     $sql .= "      inner join tipofamiliar on tipofamiliar.z14_sequencial = cgmcomposicaofamiliar.z15_cgmtipofamiliar";
     $sql2 = "";
     if($dbwhere==""){
       if($z15_sequencial!=null ){
         $sql2 .= " where cgmcomposicaofamiliar.z15_sequencial = $z15_sequencial "; 
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
   // funcao do sql 
   function sql_query_file ( $z15_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cgmcomposicaofamiliar ";
     $sql2 = "";
     if($dbwhere==""){
       if($z15_sequencial!=null ){
         $sql2 .= " where cgmcomposicaofamiliar.z15_sequencial = $z15_sequencial "; 
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