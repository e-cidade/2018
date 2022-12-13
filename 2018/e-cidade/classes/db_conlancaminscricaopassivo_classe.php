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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conlancaminscricaopassivo
class cl_conlancaminscricaopassivo { 
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
   var $c37_sequencial = 0; 
   var $c37_inscricaopassivo = 0; 
   var $c37_conlancam = 0; 
   var $c37_instit = 0; 
   var $c37_data_dia = null; 
   var $c37_data_mes = null; 
   var $c37_data_ano = null; 
   var $c37_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c37_sequencial = int4 = Sequência 
                 c37_inscricaopassivo = int4 = Inscrição Passiva 
                 c37_conlancam = int4 = Lançamento 
                 c37_instit = int4 = Instituição 
                 c37_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_conlancaminscricaopassivo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conlancaminscricaopassivo"); 
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
       $this->c37_sequencial = ($this->c37_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c37_sequencial"]:$this->c37_sequencial);
       $this->c37_inscricaopassivo = ($this->c37_inscricaopassivo == ""?@$GLOBALS["HTTP_POST_VARS"]["c37_inscricaopassivo"]:$this->c37_inscricaopassivo);
       $this->c37_conlancam = ($this->c37_conlancam == ""?@$GLOBALS["HTTP_POST_VARS"]["c37_conlancam"]:$this->c37_conlancam);
       $this->c37_instit = ($this->c37_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c37_instit"]:$this->c37_instit);
       if($this->c37_data == ""){
         $this->c37_data_dia = ($this->c37_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c37_data_dia"]:$this->c37_data_dia);
         $this->c37_data_mes = ($this->c37_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c37_data_mes"]:$this->c37_data_mes);
         $this->c37_data_ano = ($this->c37_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c37_data_ano"]:$this->c37_data_ano);
         if($this->c37_data_dia != ""){
            $this->c37_data = $this->c37_data_ano."-".$this->c37_data_mes."-".$this->c37_data_dia;
         }
       }
     }else{
       $this->c37_sequencial = ($this->c37_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c37_sequencial"]:$this->c37_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c37_sequencial){ 
      $this->atualizacampos();
     if($this->c37_inscricaopassivo == null ){ 
       $this->erro_sql = " Campo Inscrição Passiva nao Informado.";
       $this->erro_campo = "c37_inscricaopassivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c37_conlancam == null ){ 
       $this->erro_sql = " Campo Lançamento nao Informado.";
       $this->erro_campo = "c37_conlancam";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c37_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "c37_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c37_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "c37_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c37_sequencial == "" || $c37_sequencial == null ){
       $result = db_query("select nextval('conlancaminscricaopassivo_c37_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conlancaminscricaopassivo_c37_sequencial_seq do campo: c37_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c37_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from conlancaminscricaopassivo_c37_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c37_sequencial)){
         $this->erro_sql = " Campo c37_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c37_sequencial = $c37_sequencial; 
       }
     }
     if(($this->c37_sequencial == null) || ($this->c37_sequencial == "") ){ 
       $this->erro_sql = " Campo c37_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conlancaminscricaopassivo(
                                       c37_sequencial 
                                      ,c37_inscricaopassivo 
                                      ,c37_conlancam 
                                      ,c37_instit 
                                      ,c37_data 
                       )
                values (
                                $this->c37_sequencial 
                               ,$this->c37_inscricaopassivo 
                               ,$this->c37_conlancam 
                               ,$this->c37_instit 
                               ,".($this->c37_data == "null" || $this->c37_data == ""?"null":"'".$this->c37_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "vinculo com o lançamento contabil  ($this->c37_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "vinculo com o lançamento contabil  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "vinculo com o lançamento contabil  ($this->c37_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c37_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c37_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18987,'$this->c37_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3377,18987,'','".AddSlashes(pg_result($resaco,0,'c37_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3377,18988,'','".AddSlashes(pg_result($resaco,0,'c37_inscricaopassivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3377,19048,'','".AddSlashes(pg_result($resaco,0,'c37_conlancam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3377,18990,'','".AddSlashes(pg_result($resaco,0,'c37_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3377,18989,'','".AddSlashes(pg_result($resaco,0,'c37_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c37_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update conlancaminscricaopassivo set ";
     $virgula = "";
     if(trim($this->c37_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c37_sequencial"])){ 
       $sql  .= $virgula." c37_sequencial = $this->c37_sequencial ";
       $virgula = ",";
       if(trim($this->c37_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequência nao Informado.";
         $this->erro_campo = "c37_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c37_inscricaopassivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c37_inscricaopassivo"])){ 
       $sql  .= $virgula." c37_inscricaopassivo = $this->c37_inscricaopassivo ";
       $virgula = ",";
       if(trim($this->c37_inscricaopassivo) == null ){ 
         $this->erro_sql = " Campo Inscrição Passiva nao Informado.";
         $this->erro_campo = "c37_inscricaopassivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c37_conlancam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c37_conlancam"])){ 
       $sql  .= $virgula." c37_conlancam = $this->c37_conlancam ";
       $virgula = ",";
       if(trim($this->c37_conlancam) == null ){ 
         $this->erro_sql = " Campo Lançamento nao Informado.";
         $this->erro_campo = "c37_conlancam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c37_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c37_instit"])){ 
       $sql  .= $virgula." c37_instit = $this->c37_instit ";
       $virgula = ",";
       if(trim($this->c37_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "c37_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c37_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c37_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c37_data_dia"] !="") ){ 
       $sql  .= $virgula." c37_data = '$this->c37_data' ";
       $virgula = ",";
       if(trim($this->c37_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "c37_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c37_data_dia"])){ 
         $sql  .= $virgula." c37_data = null ";
         $virgula = ",";
         if(trim($this->c37_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "c37_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($c37_sequencial!=null){
       $sql .= " c37_sequencial = $this->c37_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c37_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18987,'$this->c37_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c37_sequencial"]) || $this->c37_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3377,18987,'".AddSlashes(pg_result($resaco,$conresaco,'c37_sequencial'))."','$this->c37_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c37_inscricaopassivo"]) || $this->c37_inscricaopassivo != "")
           $resac = db_query("insert into db_acount values($acount,3377,18988,'".AddSlashes(pg_result($resaco,$conresaco,'c37_inscricaopassivo'))."','$this->c37_inscricaopassivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c37_conlancam"]) || $this->c37_conlancam != "")
           $resac = db_query("insert into db_acount values($acount,3377,19048,'".AddSlashes(pg_result($resaco,$conresaco,'c37_conlancam'))."','$this->c37_conlancam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c37_instit"]) || $this->c37_instit != "")
           $resac = db_query("insert into db_acount values($acount,3377,18990,'".AddSlashes(pg_result($resaco,$conresaco,'c37_instit'))."','$this->c37_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c37_data"]) || $this->c37_data != "")
           $resac = db_query("insert into db_acount values($acount,3377,18989,'".AddSlashes(pg_result($resaco,$conresaco,'c37_data'))."','$this->c37_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "vinculo com o lançamento contabil  nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c37_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "vinculo com o lançamento contabil  nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c37_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c37_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18987,'$c37_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3377,18987,'','".AddSlashes(pg_result($resaco,$iresaco,'c37_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3377,18988,'','".AddSlashes(pg_result($resaco,$iresaco,'c37_inscricaopassivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3377,19048,'','".AddSlashes(pg_result($resaco,$iresaco,'c37_conlancam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3377,18990,'','".AddSlashes(pg_result($resaco,$iresaco,'c37_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3377,18989,'','".AddSlashes(pg_result($resaco,$iresaco,'c37_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conlancaminscricaopassivo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c37_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c37_sequencial = $c37_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "vinculo com o lançamento contabil  nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c37_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "vinculo com o lançamento contabil  nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c37_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:conlancaminscricaopassivo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c37_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conlancaminscricaopassivo ";
     $sql .= "      inner join db_config  on  db_config.codigo = conlancaminscricaopassivo.c37_instit";
     $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancaminscricaopassivo.c37_conlancam";
     $sql .= "      inner join inscricaopassivo  on  inscricaopassivo.c36_sequencial = conlancaminscricaopassivo.c37_inscricaopassivo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = inscricaopassivo.c36_cgm";
     //$sql .= "      inner join db_config  on  db_config.codigo = inscricaopassivo.c36_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inscricaopassivo.c36_db_usuarios";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = inscricaopassivo.c36_codele and  orcelemento.o56_anousu = inscricaopassivo.c36_anousu";
     $sql2 = "";
     if($dbwhere==""){
       if($c37_sequencial!=null ){
         $sql2 .= " where conlancaminscricaopassivo.c37_sequencial = $c37_sequencial "; 
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
   function sql_query_file ( $c37_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conlancaminscricaopassivo ";
     $sql2 = "";
     if($dbwhere==""){
       if($c37_sequencial!=null ){
         $sql2 .= " where conlancaminscricaopassivo.c37_sequencial = $c37_sequencial "; 
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
  function sql_query_dadoslancamento ( $c37_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
  	$sql .= " from conlancaminscricaopassivo ";
  	$sql .= "      inner join db_config  on  db_config.codigo = conlancaminscricaopassivo.c37_instit";
  	$sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancaminscricaopassivo.c37_conlancam";
  	$sql .= "      inner join inscricaopassivo  on  inscricaopassivo.c36_sequencial = conlancaminscricaopassivo.c37_inscricaopassivo";
  	$sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
  	$sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
  	$sql .= "      inner join cgm  as a on   a.z01_numcgm = inscricaopassivo.c36_cgm";
  	$sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inscricaopassivo.c36_db_usuarios";
  	$sql .= "      inner join orcelemento  on  orcelemento.o56_codele = inscricaopassivo.c36_codele and  orcelemento.o56_anousu = inscricaopassivo.c36_anousu";
  	$sql .= "      inner join conlancamcompl     					on  conlancam.c70_codlan                       = conlancamcompl.c72_codlan ";
  	$sql2 = "";
  	if($dbwhere==""){
  		if($c37_sequencial!=null ){
  			$sql2 .= " where conlancaminscricaopassivo.c37_sequencial = $c37_sequencial ";
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