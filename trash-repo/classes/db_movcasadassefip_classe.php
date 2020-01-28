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

//MODULO: pessoal
//CLASSE DA ENTIDADE movcasadassefip
class cl_movcasadassefip { 
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
   var $r67_anousu = 0; 
   var $r67_mesusu = 0; 
   var $r67_afast = null; 
   var $r67_reto = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r67_anousu = float4 = Ano do Exercício 
                 r67_mesusu = float4 = Mês do Exercício 
                 r67_afast = varchar(2) = Código do Afastamento. 
                 r67_reto = varchar(2) = Código do Retorno 
                 ";
   //funcao construtor da classe 
   function cl_movcasadassefip() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("movcasadassefip"); 
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
       $this->r67_anousu = ($this->r67_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r67_anousu"]:$this->r67_anousu);
       $this->r67_mesusu = ($this->r67_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r67_mesusu"]:$this->r67_mesusu);
       $this->r67_afast = ($this->r67_afast == ""?@$GLOBALS["HTTP_POST_VARS"]["r67_afast"]:$this->r67_afast);
       $this->r67_reto = ($this->r67_reto == ""?@$GLOBALS["HTTP_POST_VARS"]["r67_reto"]:$this->r67_reto);
     }else{
       $this->r67_anousu = ($this->r67_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r67_anousu"]:$this->r67_anousu);
       $this->r67_mesusu = ($this->r67_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r67_mesusu"]:$this->r67_mesusu);
       $this->r67_afast = ($this->r67_afast == ""?@$GLOBALS["HTTP_POST_VARS"]["r67_afast"]:$this->r67_afast);
       $this->r67_reto = ($this->r67_reto == ""?@$GLOBALS["HTTP_POST_VARS"]["r67_reto"]:$this->r67_reto);
     }
   }
   // funcao para inclusao
   function incluir ($r67_anousu,$r67_mesusu,$r67_afast,$r67_reto){ 
      $this->atualizacampos();
       $this->r67_anousu = $r67_anousu; 
       $this->r67_mesusu = $r67_mesusu; 
       $this->r67_afast = $r67_afast; 
       $this->r67_reto = $r67_reto; 
     if(($this->r67_anousu == null) || ($this->r67_anousu == "") ){ 
       $this->erro_sql = " Campo r67_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r67_mesusu == null) || ($this->r67_mesusu == "") ){ 
       $this->erro_sql = " Campo r67_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r67_afast == null) || ($this->r67_afast == "") ){ 
       $this->erro_sql = " Campo r67_afast nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r67_reto == null) || ($this->r67_reto == "") ){ 
       $this->erro_sql = " Campo r67_reto nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into movcasadassefip(
                                       r67_anousu 
                                      ,r67_mesusu 
                                      ,r67_afast 
                                      ,r67_reto 
                       )
                values (
                                $this->r67_anousu 
                               ,$this->r67_mesusu 
                               ,'$this->r67_afast' 
                               ,'$this->r67_reto' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimentações Casadas ($this->r67_anousu."-".$this->r67_mesusu."-".$this->r67_afast."-".$this->r67_reto) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimentações Casadas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimentações Casadas ($this->r67_anousu."-".$this->r67_mesusu."-".$this->r67_afast."-".$this->r67_reto) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r67_anousu."-".$this->r67_mesusu."-".$this->r67_afast."-".$this->r67_reto;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r67_anousu,$this->r67_mesusu,$this->r67_afast,$this->r67_reto));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4574,'$this->r67_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4575,'$this->r67_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4576,'$this->r67_afast','I')");
       $resac = db_query("insert into db_acountkey values($acount,4577,'$this->r67_reto','I')");
       $resac = db_query("insert into db_acount values($acount,606,4574,'','".AddSlashes(pg_result($resaco,0,'r67_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,606,4575,'','".AddSlashes(pg_result($resaco,0,'r67_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,606,4576,'','".AddSlashes(pg_result($resaco,0,'r67_afast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,606,4577,'','".AddSlashes(pg_result($resaco,0,'r67_reto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r67_anousu=null,$r67_mesusu=null,$r67_afast=null,$r67_reto=null) { 
      $this->atualizacampos();
     $sql = " update movcasadassefip set ";
     $virgula = "";
     if(trim($this->r67_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r67_anousu"])){ 
       $sql  .= $virgula." r67_anousu = $this->r67_anousu ";
       $virgula = ",";
       if(trim($this->r67_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercício nao Informado.";
         $this->erro_campo = "r67_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r67_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r67_mesusu"])){ 
       $sql  .= $virgula." r67_mesusu = $this->r67_mesusu ";
       $virgula = ",";
       if(trim($this->r67_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês do Exercício nao Informado.";
         $this->erro_campo = "r67_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r67_afast)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r67_afast"])){ 
       $sql  .= $virgula." r67_afast = '$this->r67_afast' ";
       $virgula = ",";
       if(trim($this->r67_afast) == null ){ 
         $this->erro_sql = " Campo Código do Afastamento. nao Informado.";
         $this->erro_campo = "r67_afast";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r67_reto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r67_reto"])){ 
       $sql  .= $virgula." r67_reto = '$this->r67_reto' ";
       $virgula = ",";
       if(trim($this->r67_reto) == null ){ 
         $this->erro_sql = " Campo Código do Retorno nao Informado.";
         $this->erro_campo = "r67_reto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r67_anousu!=null){
       $sql .= " r67_anousu = $this->r67_anousu";
     }
     if($r67_mesusu!=null){
       $sql .= " and  r67_mesusu = $this->r67_mesusu";
     }
     if($r67_afast!=null){
       $sql .= " and  r67_afast = '$this->r67_afast'";
     }
     if($r67_reto!=null){
       $sql .= " and  r67_reto = '$this->r67_reto'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r67_anousu,$this->r67_mesusu,$this->r67_afast,$this->r67_reto));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4574,'$this->r67_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4575,'$this->r67_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4576,'$this->r67_afast','A')");
         $resac = db_query("insert into db_acountkey values($acount,4577,'$this->r67_reto','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r67_anousu"]))
           $resac = db_query("insert into db_acount values($acount,606,4574,'".AddSlashes(pg_result($resaco,$conresaco,'r67_anousu'))."','$this->r67_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r67_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,606,4575,'".AddSlashes(pg_result($resaco,$conresaco,'r67_mesusu'))."','$this->r67_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r67_afast"]))
           $resac = db_query("insert into db_acount values($acount,606,4576,'".AddSlashes(pg_result($resaco,$conresaco,'r67_afast'))."','$this->r67_afast',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r67_reto"]))
           $resac = db_query("insert into db_acount values($acount,606,4577,'".AddSlashes(pg_result($resaco,$conresaco,'r67_reto'))."','$this->r67_reto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentações Casadas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r67_anousu."-".$this->r67_mesusu."-".$this->r67_afast."-".$this->r67_reto;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentações Casadas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r67_anousu."-".$this->r67_mesusu."-".$this->r67_afast."-".$this->r67_reto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r67_anousu."-".$this->r67_mesusu."-".$this->r67_afast."-".$this->r67_reto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r67_anousu=null,$r67_mesusu=null,$r67_afast=null,$r67_reto=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r67_anousu,$r67_mesusu,$r67_afast,$r67_reto));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4574,'$r67_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4575,'$r67_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4576,'$r67_afast','E')");
         $resac = db_query("insert into db_acountkey values($acount,4577,'$r67_reto','E')");
         $resac = db_query("insert into db_acount values($acount,606,4574,'','".AddSlashes(pg_result($resaco,$iresaco,'r67_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,606,4575,'','".AddSlashes(pg_result($resaco,$iresaco,'r67_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,606,4576,'','".AddSlashes(pg_result($resaco,$iresaco,'r67_afast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,606,4577,'','".AddSlashes(pg_result($resaco,$iresaco,'r67_reto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from movcasadassefip
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r67_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r67_anousu = $r67_anousu ";
        }
        if($r67_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r67_mesusu = $r67_mesusu ";
        }
        if($r67_afast != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r67_afast = '$r67_afast' ";
        }
        if($r67_reto != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r67_reto = '$r67_reto' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentações Casadas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r67_anousu."-".$r67_mesusu."-".$r67_afast."-".$r67_reto;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentações Casadas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r67_anousu."-".$r67_mesusu."-".$r67_afast."-".$r67_reto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r67_anousu."-".$r67_mesusu."-".$r67_afast."-".$r67_reto;
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
        $this->erro_sql   = "Record Vazio na Tabela:movcasadassefip";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function atualiza_incluir (){
  	 $this->incluir($this->r67_anousu,$this->r67_mesusu,$this->r67_afast,$this->r67_reto);
   }
   function sql_query ( $r67_anousu=null,$r67_mesusu=null,$r67_afast=null,$r67_reto=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from movcasadassefip ";
     $sql2 = "";
     if($dbwhere==""){
       if($r67_anousu!=null ){
         $sql2 .= " where movcasadassefip.r67_anousu = $r67_anousu "; 
       } 
       if($r67_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " movcasadassefip.r67_mesusu = $r67_mesusu "; 
       } 
       if($r67_afast!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " movcasadassefip.r67_afast = '$r67_afast' "; 
       } 
       if($r67_reto!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " movcasadassefip.r67_reto = '$r67_reto' "; 
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
   function sql_query_file ( $r67_anousu=null,$r67_mesusu=null,$r67_afast=null,$r67_reto=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from movcasadassefip ";
     $sql2 = "";
     if($dbwhere==""){
       if($r67_anousu!=null ){
         $sql2 .= " where movcasadassefip.r67_anousu = $r67_anousu "; 
       } 
       if($r67_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " movcasadassefip.r67_mesusu = $r67_mesusu "; 
       } 
       if($r67_afast!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " movcasadassefip.r67_afast = '$r67_afast' "; 
       } 
       if($r67_reto!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " movcasadassefip.r67_reto = '$r67_reto' "; 
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