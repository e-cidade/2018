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

//MODULO: patrim
//CLASSE DA ENTIDADE histbem
class cl_histbem { 
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
   var $t56_histbem = 0; 
   var $t56_codbem = 0; 
   var $t56_data_dia = null; 
   var $t56_data_mes = null; 
   var $t56_data_ano = null; 
   var $t56_data = null; 
   var $t56_depart = 0; 
   var $t56_situac = 0; 
   var $t56_histor = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t56_histbem = int8 = Sequencial do lançamento de histórico 
                 t56_codbem = int8 = Código do bem 
                 t56_data = date = Data 
                 t56_depart = int4 = Departamento 
                 t56_situac = int8 = Código da situação 
                 t56_histor = text = Histórico 
                 ";
   //funcao construtor da classe 
   function cl_histbem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("histbem"); 
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
       $this->t56_histbem = ($this->t56_histbem == ""?@$GLOBALS["HTTP_POST_VARS"]["t56_histbem"]:$this->t56_histbem);
       $this->t56_codbem = ($this->t56_codbem == ""?@$GLOBALS["HTTP_POST_VARS"]["t56_codbem"]:$this->t56_codbem);
       if($this->t56_data == ""){
         $this->t56_data_dia = ($this->t56_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t56_data_dia"]:$this->t56_data_dia);
         $this->t56_data_mes = ($this->t56_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t56_data_mes"]:$this->t56_data_mes);
         $this->t56_data_ano = ($this->t56_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t56_data_ano"]:$this->t56_data_ano);
         if($this->t56_data_dia != ""){
            $this->t56_data = $this->t56_data_ano."-".$this->t56_data_mes."-".$this->t56_data_dia;
         }
       }
       $this->t56_depart = ($this->t56_depart == ""?@$GLOBALS["HTTP_POST_VARS"]["t56_depart"]:$this->t56_depart);
       $this->t56_situac = ($this->t56_situac == ""?@$GLOBALS["HTTP_POST_VARS"]["t56_situac"]:$this->t56_situac);
       $this->t56_histor = ($this->t56_histor == ""?@$GLOBALS["HTTP_POST_VARS"]["t56_histor"]:$this->t56_histor);
     }else{
       $this->t56_histbem = ($this->t56_histbem == ""?@$GLOBALS["HTTP_POST_VARS"]["t56_histbem"]:$this->t56_histbem);
     }
   }
   // funcao para inclusao
   function incluir ($t56_histbem){ 
      $this->atualizacampos();
     if($this->t56_codbem == null ){ 
       $this->erro_sql = " Campo Código do bem nao Informado.";
       $this->erro_campo = "t56_codbem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t56_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "t56_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t56_depart == null ){ 
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "t56_depart";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t56_situac == null ){ 
       $this->erro_sql = " Campo Código da situação nao Informado.";
       $this->erro_campo = "t56_situac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t56_histbem == "" || $t56_histbem == null ){
       $result = db_query("select nextval('histbem_t56_histbem_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: histbem_t56_histbem_seq do campo: t56_histbem"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t56_histbem = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from histbem_t56_histbem_seq");
       if(($result != false) && (pg_result($result,0,0) < $t56_histbem)){
         $this->erro_sql = " Campo t56_histbem maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t56_histbem = $t56_histbem; 
       }
     }
     if(($this->t56_histbem == null) || ($this->t56_histbem == "") ){ 
       $this->erro_sql = " Campo t56_histbem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into histbem(
                                       t56_histbem 
                                      ,t56_codbem 
                                      ,t56_data 
                                      ,t56_depart 
                                      ,t56_situac 
                                      ,t56_histor 
                       )
                values (
                                $this->t56_histbem 
                               ,$this->t56_codbem 
                               ,".($this->t56_data == "null" || $this->t56_data == ""?"null":"'".$this->t56_data."'")." 
                               ,$this->t56_depart 
                               ,$this->t56_situac 
                               ,'$this->t56_histor' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Histórico dos bens ($this->t56_histbem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Histórico dos bens já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Histórico dos bens ($this->t56_histbem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t56_histbem;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t56_histbem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5787,'$this->t56_histbem','I')");
       $resac = db_query("insert into db_acount values($acount,919,5787,'','".AddSlashes(pg_result($resaco,0,'t56_histbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,919,5788,'','".AddSlashes(pg_result($resaco,0,'t56_codbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,919,5789,'','".AddSlashes(pg_result($resaco,0,'t56_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,919,5790,'','".AddSlashes(pg_result($resaco,0,'t56_depart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,919,5793,'','".AddSlashes(pg_result($resaco,0,'t56_situac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,919,5794,'','".AddSlashes(pg_result($resaco,0,'t56_histor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t56_histbem=null) { 
      $this->atualizacampos();
     $sql = " update histbem set ";
     $virgula = "";
     if(trim($this->t56_histbem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t56_histbem"])){ 
       $sql  .= $virgula." t56_histbem = $this->t56_histbem ";
       $virgula = ",";
       if(trim($this->t56_histbem) == null ){ 
         $this->erro_sql = " Campo Sequencial do lançamento de histórico nao Informado.";
         $this->erro_campo = "t56_histbem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t56_codbem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t56_codbem"])){ 
       $sql  .= $virgula." t56_codbem = $this->t56_codbem ";
       $virgula = ",";
       if(trim($this->t56_codbem) == null ){ 
         $this->erro_sql = " Campo Código do bem nao Informado.";
         $this->erro_campo = "t56_codbem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t56_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t56_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t56_data_dia"] !="") ){ 
       $sql  .= $virgula." t56_data = '$this->t56_data' ";
       $virgula = ",";
       if(trim($this->t56_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "t56_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t56_data_dia"])){ 
         $sql  .= $virgula." t56_data = null ";
         $virgula = ",";
         if(trim($this->t56_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "t56_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t56_depart)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t56_depart"])){ 
       $sql  .= $virgula." t56_depart = $this->t56_depart ";
       $virgula = ",";
       if(trim($this->t56_depart) == null ){ 
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "t56_depart";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t56_situac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t56_situac"])){ 
       $sql  .= $virgula." t56_situac = $this->t56_situac ";
       $virgula = ",";
       if(trim($this->t56_situac) == null ){ 
         $this->erro_sql = " Campo Código da situação nao Informado.";
         $this->erro_campo = "t56_situac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t56_histor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t56_histor"])){ 
       $sql  .= $virgula." t56_histor = '$this->t56_histor' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($t56_histbem!=null){
       $sql .= " t56_histbem = $this->t56_histbem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t56_histbem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5787,'$this->t56_histbem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t56_histbem"]))
           $resac = db_query("insert into db_acount values($acount,919,5787,'".AddSlashes(pg_result($resaco,$conresaco,'t56_histbem'))."','$this->t56_histbem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t56_codbem"]))
           $resac = db_query("insert into db_acount values($acount,919,5788,'".AddSlashes(pg_result($resaco,$conresaco,'t56_codbem'))."','$this->t56_codbem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t56_data"]))
           $resac = db_query("insert into db_acount values($acount,919,5789,'".AddSlashes(pg_result($resaco,$conresaco,'t56_data'))."','$this->t56_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t56_depart"]))
           $resac = db_query("insert into db_acount values($acount,919,5790,'".AddSlashes(pg_result($resaco,$conresaco,'t56_depart'))."','$this->t56_depart',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t56_situac"]))
           $resac = db_query("insert into db_acount values($acount,919,5793,'".AddSlashes(pg_result($resaco,$conresaco,'t56_situac'))."','$this->t56_situac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t56_histor"]))
           $resac = db_query("insert into db_acount values($acount,919,5794,'".AddSlashes(pg_result($resaco,$conresaco,'t56_histor'))."','$this->t56_histor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico dos bens nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t56_histbem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Histórico dos bens nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t56_histbem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t56_histbem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t56_histbem=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t56_histbem));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5787,'$t56_histbem','E')");
         $resac = db_query("insert into db_acount values($acount,919,5787,'','".AddSlashes(pg_result($resaco,$iresaco,'t56_histbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,919,5788,'','".AddSlashes(pg_result($resaco,$iresaco,'t56_codbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,919,5789,'','".AddSlashes(pg_result($resaco,$iresaco,'t56_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,919,5790,'','".AddSlashes(pg_result($resaco,$iresaco,'t56_depart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,919,5793,'','".AddSlashes(pg_result($resaco,$iresaco,'t56_situac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,919,5794,'','".AddSlashes(pg_result($resaco,$iresaco,'t56_histor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from histbem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t56_histbem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t56_histbem = $t56_histbem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico dos bens nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t56_histbem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Histórico dos bens nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t56_histbem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t56_histbem;
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
        $this->erro_sql   = "Record Vazio na Tabela:histbem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $t56_histbem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from histbem ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = histbem.t56_depart";
     $sql .= "      inner join bens  on  bens.t52_bem = histbem.t56_codbem";
     $sql .= "      inner join situabens  on  situabens.t70_situac = histbem.t56_situac";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_depart a  on  a.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql2 = "";
     if($dbwhere==""){
       if($t56_histbem!=null ){
         $sql2 .= " where histbem.t56_histbem = $t56_histbem "; 
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
   function sql_query_div ( $t56_histbem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from histbem ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = histbem.t56_depart";
     $sql .= "      inner join bens  on  bens.t52_bem = histbem.t56_codbem";
     $sql .= "      inner join situabens  on  situabens.t70_situac = histbem.t56_situac";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_depart a  on  a.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql .= "      left  join histbemdiv on histbem.t56_histbem = histbemdiv.t32_histbem";
     $sql .= "      left  join histbemtrans on histbemtrans.t97_histbem = histbem.t56_histbem";
     $sql .= "      left  join departdiv on departdiv.t30_codigo = histbemdiv.t32_divisao";
     $sql2 = "";
     if($dbwhere==""){
       if($t56_histbem!=null ){
         $sql2 .= " where histbem.t56_histbem = $t56_histbem "; 
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
   function sql_query_file ( $t56_histbem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from histbem ";
     $sql2 = "";
     if($dbwhere==""){
       if($t56_histbem!=null ){
         $sql2 .= " where histbem.t56_histbem = $t56_histbem "; 
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