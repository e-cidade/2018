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

//MODULO: projetos
//CLASSE DA ENTIDADE obras
class cl_obras { 
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
   var $ob01_codobra = 0; 
   var $ob01_nomeobra = null; 
   var $ob01_tiporesp = 0; 
   var $ob01_regular = 'f'; 
   var $ob01_tecnico = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ob01_codobra = int4 = Código da obra 
                 ob01_nomeobra = varchar(55) = Nome da obra 
                 ob01_tiporesp = int4 = Código do tipo de responsável 
                 ob01_regular = bool = Obra Regular 
                 ob01_tecnico = int4 = Numcgm 
                 ";
   //funcao construtor da classe 
   function cl_obras() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("obras"); 
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
       $this->ob01_codobra = ($this->ob01_codobra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_codobra"]:$this->ob01_codobra);
       $this->ob01_nomeobra = ($this->ob01_nomeobra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_nomeobra"]:$this->ob01_nomeobra);
       $this->ob01_tiporesp = ($this->ob01_tiporesp == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_tiporesp"]:$this->ob01_tiporesp);
       $this->ob01_regular = ($this->ob01_regular == "f"?@$GLOBALS["HTTP_POST_VARS"]["ob01_regular"]:$this->ob01_regular);
       $this->ob01_tecnico = ($this->ob01_tecnico == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_tecnico"]:$this->ob01_tecnico);
     }else{
       $this->ob01_codobra = ($this->ob01_codobra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_codobra"]:$this->ob01_codobra);
     }
   }
   // funcao para inclusao
   function incluir ($ob01_codobra){ 
      $this->atualizacampos();
     if($this->ob01_nomeobra == null ){ 
       $this->erro_sql = " Campo Nome da obra nao Informado.";
       $this->erro_campo = "ob01_nomeobra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob01_tiporesp == null ){ 
       $this->erro_sql = " Campo Código do tipo de responsável nao Informado.";
       $this->erro_campo = "ob01_tiporesp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob01_regular == null ){ 
       $this->erro_sql = " Campo Obra Regular nao Informado.";
       $this->erro_campo = "ob01_regular";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob01_tecnico == null ){ 
       $this->ob01_tecnico = "0";
     }
     if($ob01_codobra == "" || $ob01_codobra == null ){
       $result = @pg_query("select nextval('obras_ob01_codobra_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: obras_ob01_codobra_seq do campo: ob01_codobra"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ob01_codobra = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from obras_ob01_codobra_seq");
       if(($result != false) && (pg_result($result,0,0) < $ob01_codobra)){
         $this->erro_sql = " Campo ob01_codobra maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ob01_codobra = $ob01_codobra; 
       }
     }
     if(($this->ob01_codobra == null) || ($this->ob01_codobra == "") ){ 
       $this->erro_sql = " Campo ob01_codobra nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into obras(
                                       ob01_codobra 
                                      ,ob01_nomeobra 
                                      ,ob01_tiporesp 
                                      ,ob01_regular 
                                      ,ob01_tecnico 
                       )
                values (
                                $this->ob01_codobra 
                               ,'$this->ob01_nomeobra' 
                               ,$this->ob01_tiporesp 
                               ,'$this->ob01_regular' 
                               ,$this->ob01_tecnico 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cadastro de obras ($this->ob01_codobra) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cadastro de obras já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cadastro de obras ($this->ob01_codobra) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob01_codobra;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ob01_codobra));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,5909,'$this->ob01_codobra','I')");
       $resac = pg_query("insert into db_acount values($acount,946,5909,'','".AddSlashes(pg_result($resaco,0,'ob01_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,946,5910,'','".AddSlashes(pg_result($resaco,0,'ob01_nomeobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,946,5913,'','".AddSlashes(pg_result($resaco,0,'ob01_tiporesp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,946,5914,'','".AddSlashes(pg_result($resaco,0,'ob01_regular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,946,6193,'','".AddSlashes(pg_result($resaco,0,'ob01_tecnico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ob01_codobra=null) { 
      $this->atualizacampos();
     $sql = " update obras set ";
     $virgula = "";
     if(trim($this->ob01_codobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob01_codobra"])){ 
       $sql  .= $virgula." ob01_codobra = $this->ob01_codobra ";
       $virgula = ",";
       if(trim($this->ob01_codobra) == null ){ 
         $this->erro_sql = " Campo Código da obra nao Informado.";
         $this->erro_campo = "ob01_codobra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob01_nomeobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob01_nomeobra"])){ 
       $sql  .= $virgula." ob01_nomeobra = '$this->ob01_nomeobra' ";
       $virgula = ",";
       if(trim($this->ob01_nomeobra) == null ){ 
         $this->erro_sql = " Campo Nome da obra nao Informado.";
         $this->erro_campo = "ob01_nomeobra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob01_tiporesp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob01_tiporesp"])){ 
       $sql  .= $virgula." ob01_tiporesp = $this->ob01_tiporesp ";
       $virgula = ",";
       if(trim($this->ob01_tiporesp) == null ){ 
         $this->erro_sql = " Campo Código do tipo de responsável nao Informado.";
         $this->erro_campo = "ob01_tiporesp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob01_regular)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob01_regular"])){ 
       $sql  .= $virgula." ob01_regular = '$this->ob01_regular' ";
       $virgula = ",";
       if(trim($this->ob01_regular) == null ){ 
         $this->erro_sql = " Campo Obra Regular nao Informado.";
         $this->erro_campo = "ob01_regular";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob01_tecnico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob01_tecnico"])){ 
        if(trim($this->ob01_tecnico)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ob01_tecnico"])){ 
           $this->ob01_tecnico = "0" ; 
        } 
       $sql  .= $virgula." ob01_tecnico = $this->ob01_tecnico ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ob01_codobra!=null){
       $sql .= " ob01_codobra = $this->ob01_codobra";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ob01_codobra));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,5909,'$this->ob01_codobra','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob01_codobra"]))
           $resac = pg_query("insert into db_acount values($acount,946,5909,'".AddSlashes(pg_result($resaco,$conresaco,'ob01_codobra'))."','$this->ob01_codobra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob01_nomeobra"]))
           $resac = pg_query("insert into db_acount values($acount,946,5910,'".AddSlashes(pg_result($resaco,$conresaco,'ob01_nomeobra'))."','$this->ob01_nomeobra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob01_tiporesp"]))
           $resac = pg_query("insert into db_acount values($acount,946,5913,'".AddSlashes(pg_result($resaco,$conresaco,'ob01_tiporesp'))."','$this->ob01_tiporesp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob01_regular"]))
           $resac = pg_query("insert into db_acount values($acount,946,5914,'".AddSlashes(pg_result($resaco,$conresaco,'ob01_regular'))."','$this->ob01_regular',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob01_tecnico"]))
           $resac = pg_query("insert into db_acount values($acount,946,6193,'".AddSlashes(pg_result($resaco,$conresaco,'ob01_tecnico'))."','$this->ob01_tecnico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastro de obras nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob01_codobra;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro de obras nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob01_codobra;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob01_codobra;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ob01_codobra=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ob01_codobra));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,5909,'$this->ob01_codobra','E')");
         $resac = pg_query("insert into db_acount values($acount,946,5909,'','".AddSlashes(pg_result($resaco,$iresaco,'ob01_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,946,5910,'','".AddSlashes(pg_result($resaco,$iresaco,'ob01_nomeobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,946,5913,'','".AddSlashes(pg_result($resaco,$iresaco,'ob01_tiporesp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,946,5914,'','".AddSlashes(pg_result($resaco,$iresaco,'ob01_regular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,946,6193,'','".AddSlashes(pg_result($resaco,$iresaco,'ob01_tecnico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from obras
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ob01_codobra != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ob01_codobra = $ob01_codobra ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastro de obras nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ob01_codobra;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro de obras nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ob01_codobra;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ob01_codobra;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:obras";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ob01_codobra=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obras ";
     $sql .= "      inner join obrastiporesp  on  obrastiporesp.ob02_cod = obras.ob01_tiporesp";
     $sql .= "      inner join obrasresp on  obrasresp.ob10_codobra = obras.ob01_codobra";
     $sql .= "      inner join cgm on  obrasresp.ob10_numcgm = cgm.z01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($ob01_codobra!=null ){
         $sql2 .= " where obras.ob01_codobra = $ob01_codobra "; 
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
   function sql_query_file ( $ob01_codobra=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obras ";
     $sql2 = "";
     if($dbwhere==""){
       if($ob01_codobra!=null ){
         $sql2 .= " where obras.ob01_codobra = $ob01_codobra "; 
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
   function sql_query_infob ( $ob01_codobra=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obras ";
     $sql .= "      inner join obrastiporesp  on  obrastiporesp.ob02_cod = obras.ob01_tiporesp";
     $sql .= "      inner join obrasresp on  obrasresp.ob10_codobra = obras.ob01_codobra";
     $sql .= "      inner join cgm r on obrasresp.ob10_numcgm = r.z01_numcgm";
     $sql .= "      left  join cgm t on obras.ob01_tecnico = t.z01_numcgm";
     $sql .= "      inner join obraspropri on obras.ob01_codobra = obraspropri.ob03_codobra";    
     $sql .= "      inner join cgm p on obraspropri.ob03_numcgm = p.z01_numcgm ";
     $sql .= "      left join obraslote on obras.ob01_codobra = obraslote.ob05_codobra";
     $sql .= "      left join obraslotei on obras.ob01_codobra = obraslotei.ob06_codobra";
     $sql .= "      left join lote on obraslote.ob05_idbql = lote.j34_idbql";
     $sql .= "      left join obrasalvara on obras.ob01_codobra = obrasalvara.ob04_codobra";
     $sql .= "      inner join obrasender on obras.ob01_codobra = obrasender.ob07_codobra";
     $sql .= "      inner join ruas on obrasender.ob07_lograd = ruas.j14_codigo";
     $sql .= "      inner join bairro on obrasender.ob07_bairro = bairro.j13_codi";
     $sql2 = "";
     if($dbwhere==""){
       if($ob01_codobra!=null ){
         $sql2 .= " where obras.ob01_codobra = $ob01_codobra "; 
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