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

//MODULO: Cemiterio
//CLASSE DA ENTIDADE cemiteriorural
class cl_cemiteriorural { 
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
   var $cm16_i_cemiterio = 0; 
   var $cm16_c_nome = null; 
   var $cm16_c_endereco = null; 
   var $cm16_c_cidade = null; 
   var $cm16_c_bairro = null; 
   var $cm16_c_cep = null; 
   var $cm16_c_telefone = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cm16_i_cemiterio = int4 = Código 
                 cm16_c_nome = char(100) = Nome 
                 cm16_c_endereco = char(80) = Endereço 
                 cm16_c_cidade = char(80) = CIdade 
                 cm16_c_bairro = char(50) = Bairro 
                 cm16_c_cep = char(10) = CEP 
                 cm16_c_telefone = char(14) = Telefone 
                 ";
   //funcao construtor da classe 
   function cl_cemiteriorural() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cemiteriorural"); 
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
       $this->cm16_i_cemiterio = ($this->cm16_i_cemiterio == ""?@$GLOBALS["HTTP_POST_VARS"]["cm16_i_cemiterio"]:$this->cm16_i_cemiterio);
       $this->cm16_c_nome = ($this->cm16_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["cm16_c_nome"]:$this->cm16_c_nome);
       $this->cm16_c_endereco = ($this->cm16_c_endereco == ""?@$GLOBALS["HTTP_POST_VARS"]["cm16_c_endereco"]:$this->cm16_c_endereco);
       $this->cm16_c_cidade = ($this->cm16_c_cidade == ""?@$GLOBALS["HTTP_POST_VARS"]["cm16_c_cidade"]:$this->cm16_c_cidade);
       $this->cm16_c_bairro = ($this->cm16_c_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["cm16_c_bairro"]:$this->cm16_c_bairro);
       $this->cm16_c_cep = ($this->cm16_c_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["cm16_c_cep"]:$this->cm16_c_cep);
       $this->cm16_c_telefone = ($this->cm16_c_telefone == ""?@$GLOBALS["HTTP_POST_VARS"]["cm16_c_telefone"]:$this->cm16_c_telefone);
     }else{
       $this->cm16_i_cemiterio = ($this->cm16_i_cemiterio == ""?@$GLOBALS["HTTP_POST_VARS"]["cm16_i_cemiterio"]:$this->cm16_i_cemiterio);
     }
   }
   // funcao para inclusao
   function incluir ($cm16_i_cemiterio){ 
      $this->atualizacampos();
     if($this->cm16_c_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "cm16_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm16_c_endereco == null ){ 
       $this->erro_sql = " Campo Endereço nao Informado.";
       $this->erro_campo = "cm16_c_endereco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm16_c_cidade == null ){ 
       $this->erro_sql = " Campo CIdade nao Informado.";
       $this->erro_campo = "cm16_c_cidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->cm16_i_cemiterio = $cm16_i_cemiterio; 
     if(($this->cm16_i_cemiterio == null) || ($this->cm16_i_cemiterio == "") ){ 
       $this->erro_sql = " Campo cm16_i_cemiterio nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cemiteriorural(
                                       cm16_i_cemiterio 
                                      ,cm16_c_nome 
                                      ,cm16_c_endereco 
                                      ,cm16_c_cidade 
                                      ,cm16_c_bairro 
                                      ,cm16_c_cep 
                                      ,cm16_c_telefone 
                       )
                values (
                                $this->cm16_i_cemiterio 
                               ,'$this->cm16_c_nome' 
                               ,'$this->cm16_c_endereco' 
                               ,'$this->cm16_c_cidade' 
                               ,'$this->cm16_c_bairro' 
                               ,'$this->cm16_c_cep' 
                               ,'$this->cm16_c_telefone' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cemitérios fora da cidade ($this->cm16_i_cemiterio) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cemitérios fora da cidade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cemitérios fora da cidade ($this->cm16_i_cemiterio) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm16_i_cemiterio;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm16_i_cemiterio));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10295,'$this->cm16_i_cemiterio','I')");
       $resac = db_query("insert into db_acount values($acount,1782,10295,'','".AddSlashes(pg_result($resaco,0,'cm16_i_cemiterio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1782,10296,'','".AddSlashes(pg_result($resaco,0,'cm16_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1782,10297,'','".AddSlashes(pg_result($resaco,0,'cm16_c_endereco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1782,10299,'','".AddSlashes(pg_result($resaco,0,'cm16_c_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1782,10300,'','".AddSlashes(pg_result($resaco,0,'cm16_c_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1782,10298,'','".AddSlashes(pg_result($resaco,0,'cm16_c_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1782,10301,'','".AddSlashes(pg_result($resaco,0,'cm16_c_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cm16_i_cemiterio=null) { 
      $this->atualizacampos();
     $sql = " update cemiteriorural set ";
     $virgula = "";
     if(trim($this->cm16_i_cemiterio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm16_i_cemiterio"])){ 
       $sql  .= $virgula." cm16_i_cemiterio = $this->cm16_i_cemiterio ";
       $virgula = ",";
       if(trim($this->cm16_i_cemiterio) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "cm16_i_cemiterio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm16_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm16_c_nome"])){ 
       $sql  .= $virgula." cm16_c_nome = '$this->cm16_c_nome' ";
       $virgula = ",";
       if(trim($this->cm16_c_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "cm16_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm16_c_endereco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm16_c_endereco"])){ 
       $sql  .= $virgula." cm16_c_endereco = '$this->cm16_c_endereco' ";
       $virgula = ",";
       if(trim($this->cm16_c_endereco) == null ){ 
         $this->erro_sql = " Campo Endereço nao Informado.";
         $this->erro_campo = "cm16_c_endereco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm16_c_cidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm16_c_cidade"])){ 
       $sql  .= $virgula." cm16_c_cidade = '$this->cm16_c_cidade' ";
       $virgula = ",";
       if(trim($this->cm16_c_cidade) == null ){ 
         $this->erro_sql = " Campo CIdade nao Informado.";
         $this->erro_campo = "cm16_c_cidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm16_c_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm16_c_bairro"])){ 
       $sql  .= $virgula." cm16_c_bairro = '$this->cm16_c_bairro' ";
       $virgula = ",";
     }
     if(trim($this->cm16_c_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm16_c_cep"])){ 
       $sql  .= $virgula." cm16_c_cep = '$this->cm16_c_cep' ";
       $virgula = ",";
     }
     if(trim($this->cm16_c_telefone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm16_c_telefone"])){ 
       $sql  .= $virgula." cm16_c_telefone = '$this->cm16_c_telefone' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($cm16_i_cemiterio!=null){
       $sql .= " cm16_i_cemiterio = $this->cm16_i_cemiterio";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm16_i_cemiterio));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10295,'$this->cm16_i_cemiterio','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm16_i_cemiterio"]))
           $resac = db_query("insert into db_acount values($acount,1782,10295,'".AddSlashes(pg_result($resaco,$conresaco,'cm16_i_cemiterio'))."','$this->cm16_i_cemiterio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm16_c_nome"]))
           $resac = db_query("insert into db_acount values($acount,1782,10296,'".AddSlashes(pg_result($resaco,$conresaco,'cm16_c_nome'))."','$this->cm16_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm16_c_endereco"]))
           $resac = db_query("insert into db_acount values($acount,1782,10297,'".AddSlashes(pg_result($resaco,$conresaco,'cm16_c_endereco'))."','$this->cm16_c_endereco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm16_c_cidade"]))
           $resac = db_query("insert into db_acount values($acount,1782,10299,'".AddSlashes(pg_result($resaco,$conresaco,'cm16_c_cidade'))."','$this->cm16_c_cidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm16_c_bairro"]))
           $resac = db_query("insert into db_acount values($acount,1782,10300,'".AddSlashes(pg_result($resaco,$conresaco,'cm16_c_bairro'))."','$this->cm16_c_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm16_c_cep"]))
           $resac = db_query("insert into db_acount values($acount,1782,10298,'".AddSlashes(pg_result($resaco,$conresaco,'cm16_c_cep'))."','$this->cm16_c_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm16_c_telefone"]))
           $resac = db_query("insert into db_acount values($acount,1782,10301,'".AddSlashes(pg_result($resaco,$conresaco,'cm16_c_telefone'))."','$this->cm16_c_telefone',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cemitérios fora da cidade nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm16_i_cemiterio;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cemitérios fora da cidade nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm16_i_cemiterio;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm16_i_cemiterio;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cm16_i_cemiterio=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm16_i_cemiterio));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10295,'$cm16_i_cemiterio','E')");
         $resac = db_query("insert into db_acount values($acount,1782,10295,'','".AddSlashes(pg_result($resaco,$iresaco,'cm16_i_cemiterio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1782,10296,'','".AddSlashes(pg_result($resaco,$iresaco,'cm16_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1782,10297,'','".AddSlashes(pg_result($resaco,$iresaco,'cm16_c_endereco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1782,10299,'','".AddSlashes(pg_result($resaco,$iresaco,'cm16_c_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1782,10300,'','".AddSlashes(pg_result($resaco,$iresaco,'cm16_c_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1782,10298,'','".AddSlashes(pg_result($resaco,$iresaco,'cm16_c_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1782,10301,'','".AddSlashes(pg_result($resaco,$iresaco,'cm16_c_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cemiteriorural
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm16_i_cemiterio != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm16_i_cemiterio = $cm16_i_cemiterio ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cemitérios fora da cidade nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm16_i_cemiterio;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cemitérios fora da cidade nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm16_i_cemiterio;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm16_i_cemiterio;
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
        $this->erro_sql   = "Record Vazio na Tabela:cemiteriorural";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $cm16_i_cemiterio=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cemiteriorural ";
     $sql .= "      inner join cemiterio  on  cemiterio.cm14_i_codigo = cemiteriorural.cm16_i_cemiterio";
     $sql2 = "";
     if($dbwhere==""){
       if($cm16_i_cemiterio!=null ){
         $sql2 .= " where cemiteriorural.cm16_i_cemiterio = $cm16_i_cemiterio "; 
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
   function sql_query_file ( $cm16_i_cemiterio=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cemiteriorural ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm16_i_cemiterio!=null ){
         $sql2 .= " where cemiteriorural.cm16_i_cemiterio = $cm16_i_cemiterio "; 
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