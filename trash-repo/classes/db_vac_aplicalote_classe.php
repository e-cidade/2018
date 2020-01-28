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

//MODULO: vacinas
//CLASSE DA ENTIDADE vac_aplicalote
class cl_vac_aplicalote { 
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
   var $vc17_i_codigo = 0; 
   var $vc17_i_aplica = 0; 
   var $vc17_i_sala = 0; 
   var $vc17_i_matetoqueitemlote = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 vc17_i_codigo = int4 = Código 
                 vc17_i_aplica = int4 = Aplicação 
                 vc17_i_sala = int4 = Sala 
                 vc17_i_matetoqueitemlote = int4 = Lote 
                 ";
   //funcao construtor da classe 
   function cl_vac_aplicalote() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vac_aplicalote"); 
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
       $this->vc17_i_codigo = ($this->vc17_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc17_i_codigo"]:$this->vc17_i_codigo);
       $this->vc17_i_aplica = ($this->vc17_i_aplica == ""?@$GLOBALS["HTTP_POST_VARS"]["vc17_i_aplica"]:$this->vc17_i_aplica);
       $this->vc17_i_sala = ($this->vc17_i_sala == ""?@$GLOBALS["HTTP_POST_VARS"]["vc17_i_sala"]:$this->vc17_i_sala);
       $this->vc17_i_matetoqueitemlote = ($this->vc17_i_matetoqueitemlote == ""?@$GLOBALS["HTTP_POST_VARS"]["vc17_i_matetoqueitemlote"]:$this->vc17_i_matetoqueitemlote);
     }else{
       $this->vc17_i_codigo = ($this->vc17_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc17_i_codigo"]:$this->vc17_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($vc17_i_codigo){ 
      $this->atualizacampos();
     if($this->vc17_i_aplica == null ){ 
       $this->erro_sql = " Campo Aplicação nao Informado.";
       $this->erro_campo = "vc17_i_aplica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc17_i_sala == null ){ 
       $this->erro_sql = " Campo Sala nao Informado.";
       $this->erro_campo = "vc17_i_sala";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc17_i_matetoqueitemlote == null ){ 
       $this->erro_sql = " Campo Lote nao Informado.";
       $this->erro_campo = "vc17_i_matetoqueitemlote";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($vc17_i_codigo == "" || $vc17_i_codigo == null ){
       $result = db_query("select nextval('vac_aplicalote_vc17_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vac_aplicalote_vc17_i_codigo_seq do campo: vc17_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->vc17_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vac_aplicalote_vc17_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $vc17_i_codigo)){
         $this->erro_sql = " Campo vc17_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->vc17_i_codigo = $vc17_i_codigo; 
       }
     }
     if(($this->vc17_i_codigo == null) || ($this->vc17_i_codigo == "") ){ 
       $this->erro_sql = " Campo vc17_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vac_aplicalote(
                                       vc17_i_codigo 
                                      ,vc17_i_aplica 
                                      ,vc17_i_sala 
                                      ,vc17_i_matetoqueitemlote 
                       )
                values (
                                $this->vc17_i_codigo 
                               ,$this->vc17_i_aplica 
                               ,$this->vc17_i_sala 
                               ,$this->vc17_i_matetoqueitemlote 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Aplica Lote ($this->vc17_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Aplica Lote já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Aplica Lote ($this->vc17_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc17_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->vc17_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16883,'$this->vc17_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2971,16883,'','".AddSlashes(pg_result($resaco,0,'vc17_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2971,16885,'','".AddSlashes(pg_result($resaco,0,'vc17_i_aplica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2971,16886,'','".AddSlashes(pg_result($resaco,0,'vc17_i_sala'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2971,17507,'','".AddSlashes(pg_result($resaco,0,'vc17_i_matetoqueitemlote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($vc17_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update vac_aplicalote set ";
     $virgula = "";
     if(trim($this->vc17_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc17_i_codigo"])){ 
       $sql  .= $virgula." vc17_i_codigo = $this->vc17_i_codigo ";
       $virgula = ",";
       if(trim($this->vc17_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "vc17_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc17_i_aplica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc17_i_aplica"])){ 
       $sql  .= $virgula." vc17_i_aplica = $this->vc17_i_aplica ";
       $virgula = ",";
       if(trim($this->vc17_i_aplica) == null ){ 
         $this->erro_sql = " Campo Aplicação nao Informado.";
         $this->erro_campo = "vc17_i_aplica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc17_i_sala)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc17_i_sala"])){ 
       $sql  .= $virgula." vc17_i_sala = $this->vc17_i_sala ";
       $virgula = ",";
       if(trim($this->vc17_i_sala) == null ){ 
         $this->erro_sql = " Campo Sala nao Informado.";
         $this->erro_campo = "vc17_i_sala";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc17_i_matetoqueitemlote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc17_i_matetoqueitemlote"])){ 
       $sql  .= $virgula." vc17_i_matetoqueitemlote = $this->vc17_i_matetoqueitemlote ";
       $virgula = ",";
       if(trim($this->vc17_i_matetoqueitemlote) == null ){ 
         $this->erro_sql = " Campo Lote nao Informado.";
         $this->erro_campo = "vc17_i_matetoqueitemlote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($vc17_i_codigo!=null){
       $sql .= " vc17_i_codigo = $this->vc17_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->vc17_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16883,'$this->vc17_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc17_i_codigo"]) || $this->vc17_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2971,16883,'".AddSlashes(pg_result($resaco,$conresaco,'vc17_i_codigo'))."','$this->vc17_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc17_i_aplica"]) || $this->vc17_i_aplica != "")
           $resac = db_query("insert into db_acount values($acount,2971,16885,'".AddSlashes(pg_result($resaco,$conresaco,'vc17_i_aplica'))."','$this->vc17_i_aplica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc17_i_sala"]) || $this->vc17_i_sala != "")
           $resac = db_query("insert into db_acount values($acount,2971,16886,'".AddSlashes(pg_result($resaco,$conresaco,'vc17_i_sala'))."','$this->vc17_i_sala',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc17_i_matetoqueitemlote"]) || $this->vc17_i_matetoqueitemlote != "")
           $resac = db_query("insert into db_acount values($acount,2971,17507,'".AddSlashes(pg_result($resaco,$conresaco,'vc17_i_matetoqueitemlote'))."','$this->vc17_i_matetoqueitemlote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Aplica Lote nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc17_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Aplica Lote nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc17_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc17_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($vc17_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($vc17_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16883,'$vc17_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2971,16883,'','".AddSlashes(pg_result($resaco,$iresaco,'vc17_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2971,16885,'','".AddSlashes(pg_result($resaco,$iresaco,'vc17_i_aplica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2971,16886,'','".AddSlashes(pg_result($resaco,$iresaco,'vc17_i_sala'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2971,17507,'','".AddSlashes(pg_result($resaco,$iresaco,'vc17_i_matetoqueitemlote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vac_aplicalote
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($vc17_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " vc17_i_codigo = $vc17_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Aplica Lote nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$vc17_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Aplica Lote nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$vc17_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$vc17_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:vac_aplicalote";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $vc17_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_aplicalote ";
     $sql .= "      inner join matestoqueitemlote  on  matestoqueitemlote.m77_sequencial = vac_aplicalote.vc17_i_matetoqueitemlote";
     $sql .= "      inner join vac_sala  on  vac_sala.vc01_i_codigo = vac_aplicalote.vc17_i_sala";
     $sql .= "      inner join vac_aplica  on  vac_aplica.vc16_i_codigo = vac_aplicalote.vc17_i_aplica";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueitemlote.m77_matestoqueitem";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = vac_sala.vc01_i_unidade";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = vac_aplica.vc16_i_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = vac_aplica.vc16_i_departamento";
     $sql .= "      inner join vac_vacinadose  on  vac_vacinadose.vc07_i_codigo = vac_aplica.vc16_i_dosevacina";
     $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = vac_aplica.vc16_i_cgs";
     $sql2 = "";
     if($dbwhere==""){
       if($vc17_i_codigo!=null ){
         $sql2 .= " where vac_aplicalote.vc17_i_codigo = $vc17_i_codigo "; 
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
   function sql_query_file ( $vc17_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_aplicalote ";
     $sql2 = "";
     if($dbwhere==""){
       if($vc17_i_codigo!=null ){
         $sql2 .= " where vac_aplicalote.vc17_i_codigo = $vc17_i_codigo "; 
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
function sql_query2 ( $vc17_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
  $sql .= " from vac_aplicalote ";
  $sql .= "      inner join matestoqueitemlote  on  matestoqueitemlote.m77_sequencial = vac_aplicalote.vc17_i_matetoqueitemlote";
  $sql .= "      inner join vac_sala            on  vac_sala.vc01_i_codigo = vac_aplicalote.vc17_i_sala";
  $sql .= "      inner join vac_aplica          on  vac_aplica.vc16_i_codigo = vac_aplicalote.vc17_i_aplica";
  $sql .= "      inner join matestoqueitem      on  matestoqueitem.m71_codlanc = matestoqueitemlote.m77_matestoqueitem";
  $sql .= "      inner join matestoque          on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque ";
  $sql .= "      inner join unidades            on  unidades.sd02_i_codigo = vac_sala.vc01_i_unidade";
  $sql .= "      inner join db_usuarios         on  db_usuarios.id_usuario = vac_aplica.vc16_i_usuario";
  $sql .= "      inner join db_depart           on  db_depart.coddepto = vac_aplica.vc16_i_departamento";
  $sql .= "      inner join vac_vacinadose      on  vac_vacinadose.vc07_i_codigo = vac_aplica.vc16_i_dosevacina";
  $sql .= "      inner join vac_vacina          on  vac_vacina.vc06_i_codigo = vac_vacinadose.vc07_i_vacina";
  $sql .= "      inner join vac_vacinamaterial  on  vc29_i_vacina = vac_vacina.vc06_i_codigo "; 
  $sql .= "                                    and  vc29_i_material = matestoque.m70_codmatmater";
  $sql .= "      inner join vac_dose            on  vac_dose.vc03_i_codigo = vac_vacinadose.vc07_i_dose";
  $sql .= "      inner join cgs                 on  cgs.z01_i_numcgs = vac_aplica.vc16_i_cgs";
  $sql .= "      inner join cgs_und             on  cgs_und.z01_i_cgsund = cgs.z01_i_numcgs";
  $sql2 = "";
  if($dbwhere==""){
    if($vc17_i_codigo!=null ){
      $sql2 .= " where vac_aplicalote.vc17_i_codigo = $vc17_i_codigo "; 
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