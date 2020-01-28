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
//CLASSE DA ENTIDADE pcorcamtroca
class cl_pcorcamtroca { 
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
   var $pc25_codtroca = 0; 
   var $pc25_orcamitem = 0; 
   var $pc25_motivo = null; 
   var $pc25_forneant = 0; 
   var $pc25_forneatu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc25_codtroca = int4 = Código sequencial do registro de troca 
                 pc25_orcamitem = int4 = Código sequencial do item no orçamento 
                 pc25_motivo = text = Motivo da troca de pontuação 
                 pc25_forneant = int8 = Fornecedor Julgado anteriormente 
                 pc25_forneatu = int8 = Fornecedor trocado após julgamento 
                 ";
   //funcao construtor da classe 
   function cl_pcorcamtroca() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcorcamtroca"); 
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
       $this->pc25_codtroca = ($this->pc25_codtroca == ""?@$GLOBALS["HTTP_POST_VARS"]["pc25_codtroca"]:$this->pc25_codtroca);
       $this->pc25_orcamitem = ($this->pc25_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc25_orcamitem"]:$this->pc25_orcamitem);
       $this->pc25_motivo = ($this->pc25_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc25_motivo"]:$this->pc25_motivo);
       $this->pc25_forneant = ($this->pc25_forneant == ""?@$GLOBALS["HTTP_POST_VARS"]["pc25_forneant"]:$this->pc25_forneant);
       $this->pc25_forneatu = ($this->pc25_forneatu == ""?@$GLOBALS["HTTP_POST_VARS"]["pc25_forneatu"]:$this->pc25_forneatu);
     }else{
       $this->pc25_codtroca = ($this->pc25_codtroca == ""?@$GLOBALS["HTTP_POST_VARS"]["pc25_codtroca"]:$this->pc25_codtroca);
     }
   }
   // funcao para inclusao
   function incluir ($pc25_codtroca){ 
      $this->atualizacampos();
     if($this->pc25_orcamitem == null ){ 
       $this->erro_sql = " Campo Código sequencial do item no orçamento nao Informado.";
       $this->erro_campo = "pc25_orcamitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc25_motivo == null ){ 
       $this->erro_sql = " Campo Motivo da troca de pontuação nao Informado.";
       $this->erro_campo = "pc25_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc25_forneant == null ){ 
       $this->erro_sql = " Campo Fornecedor Julgado anteriormente nao Informado.";
       $this->erro_campo = "pc25_forneant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc25_forneatu == null ){ 
       $this->erro_sql = " Campo Fornecedor trocado após julgamento nao Informado.";
       $this->erro_campo = "pc25_forneatu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc25_codtroca == "" || $pc25_codtroca == null ){
       $result = db_query("select nextval('pcorcamtroca_pc25_codtroca_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcorcamtroca_pc25_codtroca_seq do campo: pc25_codtroca"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc25_codtroca = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pcorcamtroca_pc25_codtroca_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc25_codtroca)){
         $this->erro_sql = " Campo pc25_codtroca maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc25_codtroca = $pc25_codtroca; 
       }
     }
     if(($this->pc25_codtroca == null) || ($this->pc25_codtroca == "") ){ 
       $this->erro_sql = " Campo pc25_codtroca nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcorcamtroca(
                                       pc25_codtroca 
                                      ,pc25_orcamitem 
                                      ,pc25_motivo 
                                      ,pc25_forneant 
                                      ,pc25_forneatu 
                       )
                values (
                                $this->pc25_codtroca 
                               ,$this->pc25_orcamitem 
                               ,'$this->pc25_motivo' 
                               ,$this->pc25_forneant 
                               ,$this->pc25_forneatu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Troca de pontuação dos orçamentos ($this->pc25_codtroca) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Troca de pontuação dos orçamentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Troca de pontuação dos orçamentos ($this->pc25_codtroca) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc25_codtroca;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc25_codtroca));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5521,'$this->pc25_codtroca','I')");
       $resac = db_query("insert into db_acount values($acount,862,5521,'','".AddSlashes(pg_result($resaco,0,'pc25_codtroca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,862,5522,'','".AddSlashes(pg_result($resaco,0,'pc25_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,862,5523,'','".AddSlashes(pg_result($resaco,0,'pc25_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,862,10622,'','".AddSlashes(pg_result($resaco,0,'pc25_forneant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,862,10623,'','".AddSlashes(pg_result($resaco,0,'pc25_forneatu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc25_codtroca=null) { 
      $this->atualizacampos();
     $sql = " update pcorcamtroca set ";
     $virgula = "";
     if(trim($this->pc25_codtroca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc25_codtroca"])){ 
       $sql  .= $virgula." pc25_codtroca = $this->pc25_codtroca ";
       $virgula = ",";
       if(trim($this->pc25_codtroca) == null ){ 
         $this->erro_sql = " Campo Código sequencial do registro de troca nao Informado.";
         $this->erro_campo = "pc25_codtroca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc25_orcamitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc25_orcamitem"])){ 
       $sql  .= $virgula." pc25_orcamitem = $this->pc25_orcamitem ";
       $virgula = ",";
       if(trim($this->pc25_orcamitem) == null ){ 
         $this->erro_sql = " Campo Código sequencial do item no orçamento nao Informado.";
         $this->erro_campo = "pc25_orcamitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc25_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc25_motivo"])){ 
       $sql  .= $virgula." pc25_motivo = '$this->pc25_motivo' ";
       $virgula = ",";
       if(trim($this->pc25_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo da troca de pontuação nao Informado.";
         $this->erro_campo = "pc25_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc25_forneant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc25_forneant"])){ 
       $sql  .= $virgula." pc25_forneant = $this->pc25_forneant ";
       $virgula = ",";
       if(trim($this->pc25_forneant) == null ){ 
         $this->erro_sql = " Campo Fornecedor Julgado anteriormente nao Informado.";
         $this->erro_campo = "pc25_forneant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc25_forneatu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc25_forneatu"])){ 
       $sql  .= $virgula." pc25_forneatu = $this->pc25_forneatu ";
       $virgula = ",";
       if(trim($this->pc25_forneatu) == null ){ 
         $this->erro_sql = " Campo Fornecedor trocado após julgamento nao Informado.";
         $this->erro_campo = "pc25_forneatu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc25_codtroca!=null){
       $sql .= " pc25_codtroca = $this->pc25_codtroca";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc25_codtroca));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5521,'$this->pc25_codtroca','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc25_codtroca"]))
           $resac = db_query("insert into db_acount values($acount,862,5521,'".AddSlashes(pg_result($resaco,$conresaco,'pc25_codtroca'))."','$this->pc25_codtroca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc25_orcamitem"]))
           $resac = db_query("insert into db_acount values($acount,862,5522,'".AddSlashes(pg_result($resaco,$conresaco,'pc25_orcamitem'))."','$this->pc25_orcamitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc25_motivo"]))
           $resac = db_query("insert into db_acount values($acount,862,5523,'".AddSlashes(pg_result($resaco,$conresaco,'pc25_motivo'))."','$this->pc25_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc25_forneant"]))
           $resac = db_query("insert into db_acount values($acount,862,10622,'".AddSlashes(pg_result($resaco,$conresaco,'pc25_forneant'))."','$this->pc25_forneant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc25_forneatu"]))
           $resac = db_query("insert into db_acount values($acount,862,10623,'".AddSlashes(pg_result($resaco,$conresaco,'pc25_forneatu'))."','$this->pc25_forneatu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Troca de pontuação dos orçamentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc25_codtroca;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Troca de pontuação dos orçamentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc25_codtroca;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc25_codtroca;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc25_codtroca=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc25_codtroca));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5521,'$pc25_codtroca','E')");
         $resac = db_query("insert into db_acount values($acount,862,5521,'','".AddSlashes(pg_result($resaco,$iresaco,'pc25_codtroca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,862,5522,'','".AddSlashes(pg_result($resaco,$iresaco,'pc25_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,862,5523,'','".AddSlashes(pg_result($resaco,$iresaco,'pc25_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,862,10622,'','".AddSlashes(pg_result($resaco,$iresaco,'pc25_forneant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,862,10623,'','".AddSlashes(pg_result($resaco,$iresaco,'pc25_forneatu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcorcamtroca
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc25_codtroca != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc25_codtroca = $pc25_codtroca ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Troca de pontuação dos orçamentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc25_codtroca;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Troca de pontuação dos orçamentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc25_codtroca;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc25_codtroca;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcorcamtroca";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc25_codtroca=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcorcamtroca ";
     $sql .= "      inner join pcorcamitem on pcorcamitem.pc22_orcamitem = pcorcamtroca.pc25_orcamitem";
     $sql .= "      inner join pcorcam     on pcorcam.pc20_codorc        = pcorcamitem.pc22_codorc";
     $sql .= "      left  join pcorcamforne as forneant on forneant.pc21_orcamforne = pcorcamtroca.pc25_forneant"; 
     $sql .= "      left  join pcorcamforne as forneatu on forneatu.pc21_orcamforne = pcorcamtroca.pc25_forneatu";
     $sql .= "      left  join cgm          on cgm.z01_numcgm = forneant.pc21_numcgm";
     $sql .= "      left  join cgm as a     on a.z01_numcgm   = forneatu.pc21_numcgm";
     $sql .= "      left  join pcorcam as b on b.pc20_codorc  = forneant.pc21_codorc";
     $sql .= "      left  join pcorcam as c on c.pc20_codorc  = forneatu.pc21_codorc";
     $sql2 = "";
     if($dbwhere==""){
       if($pc25_codtroca!=null ){
         $sql2 .= " where pcorcamtroca.pc25_codtroca = $pc25_codtroca "; 
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
   function sql_query_file ( $pc25_codtroca=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcorcamtroca ";
     $sql .= "      left join pcorcamitem on pcorcamitem.pc22_orcamitem = pcorcamtroca.pc25_orcamitem ";
     $sql .= "      left join pcorcamitemsol on pcorcamitemsol.pc29_orcamitem = pcorcamitem.pc22_orcamitem ";
     $sql .= "      left join solicitem on solicitem.pc11_codigo = pcorcamitemsol.pc29_solicitem ";
     $sql .= "      left join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo ";
     $sql .= "      left join pcmater on pcmater.pc01_codmater = solicitempcmater.pc16_codmater ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc25_codtroca!=null ){
         $sql2 .= " where pcorcamtroca.pc25_codtroca = $pc25_codtroca "; 
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