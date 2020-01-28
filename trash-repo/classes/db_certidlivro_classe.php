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

//MODULO: divida
//CLASSE DA ENTIDADE certidlivro
class cl_certidlivro { 
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
   var $v25_sequencial = 0; 
   var $v25_usuario = 0; 
   var $v25_datainc_dia = null; 
   var $v25_datainc_mes = null; 
   var $v25_datainc_ano = null; 
   var $v25_datainc = null; 
   var $v25_hora = null; 
   var $v25_numero = 0; 
   var $v25_instit = 0; 
   var $v25_tipolivro = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v25_sequencial = int4 = Código Sequencial 
                 v25_usuario = int4 = Usuário 
                 v25_datainc = date = Data de Inclusão 
                 v25_hora = char(5) = Hora 
                 v25_numero = int4 = Livro 
                 v25_instit = int4 = Instituição 
                 v25_tipolivro = int4 = Tipo do Livro 
                 ";
   //funcao construtor da classe 
   function cl_certidlivro() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("certidlivro"); 
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
       $this->v25_sequencial = ($this->v25_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v25_sequencial"]:$this->v25_sequencial);
       $this->v25_usuario = ($this->v25_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["v25_usuario"]:$this->v25_usuario);
       if($this->v25_datainc == ""){
         $this->v25_datainc_dia = ($this->v25_datainc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v25_datainc_dia"]:$this->v25_datainc_dia);
         $this->v25_datainc_mes = ($this->v25_datainc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v25_datainc_mes"]:$this->v25_datainc_mes);
         $this->v25_datainc_ano = ($this->v25_datainc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v25_datainc_ano"]:$this->v25_datainc_ano);
         if($this->v25_datainc_dia != ""){
            $this->v25_datainc = $this->v25_datainc_ano."-".$this->v25_datainc_mes."-".$this->v25_datainc_dia;
         }
       }
       $this->v25_hora = ($this->v25_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["v25_hora"]:$this->v25_hora);
       $this->v25_numero = ($this->v25_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["v25_numero"]:$this->v25_numero);
       $this->v25_instit = ($this->v25_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["v25_instit"]:$this->v25_instit);
       $this->v25_tipolivro = ($this->v25_tipolivro == ""?@$GLOBALS["HTTP_POST_VARS"]["v25_tipolivro"]:$this->v25_tipolivro);
     }else{
       $this->v25_sequencial = ($this->v25_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v25_sequencial"]:$this->v25_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v25_sequencial){ 
      $this->atualizacampos();
     if($this->v25_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "v25_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v25_datainc == null ){ 
       $this->erro_sql = " Campo Data de Inclusão nao Informado.";
       $this->erro_campo = "v25_datainc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v25_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "v25_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v25_numero == null ){ 
       $this->erro_sql = " Campo Livro nao Informado.";
       $this->erro_campo = "v25_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v25_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "v25_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v25_tipolivro == null ){ 
       $this->erro_sql = " Campo Tipo do Livro nao Informado.";
       $this->erro_campo = "v25_tipolivro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v25_sequencial == "" || $v25_sequencial == null ){
       $result = db_query("select nextval('certidlivro_v25_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: certidlivro_v25_sequencial_seq do campo: v25_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v25_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from certidlivro_v25_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v25_sequencial)){
         $this->erro_sql = " Campo v25_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v25_sequencial = $v25_sequencial; 
       }
     }
     if(($this->v25_sequencial == null) || ($this->v25_sequencial == "") ){ 
       $this->erro_sql = " Campo v25_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into certidlivro(
                                       v25_sequencial 
                                      ,v25_usuario 
                                      ,v25_datainc 
                                      ,v25_hora 
                                      ,v25_numero 
                                      ,v25_instit 
                                      ,v25_tipolivro 
                       )
                values (
                                $this->v25_sequencial 
                               ,$this->v25_usuario 
                               ,".($this->v25_datainc == "null" || $this->v25_datainc == ""?"null":"'".$this->v25_datainc."'")." 
                               ,'$this->v25_hora' 
                               ,$this->v25_numero 
                               ,$this->v25_instit 
                               ,$this->v25_tipolivro 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Livro de CDA ($this->v25_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Livro de CDA já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Livro de CDA ($this->v25_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v25_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v25_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14833,'$this->v25_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2611,14833,'','".AddSlashes(pg_result($resaco,0,'v25_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2611,14834,'','".AddSlashes(pg_result($resaco,0,'v25_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2611,14835,'','".AddSlashes(pg_result($resaco,0,'v25_datainc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2611,14836,'','".AddSlashes(pg_result($resaco,0,'v25_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2611,14837,'','".AddSlashes(pg_result($resaco,0,'v25_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2611,14856,'','".AddSlashes(pg_result($resaco,0,'v25_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2611,14857,'','".AddSlashes(pg_result($resaco,0,'v25_tipolivro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v25_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update certidlivro set ";
     $virgula = "";
     if(trim($this->v25_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v25_sequencial"])){ 
       $sql  .= $virgula." v25_sequencial = $this->v25_sequencial ";
       $virgula = ",";
       if(trim($this->v25_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "v25_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v25_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v25_usuario"])){ 
       $sql  .= $virgula." v25_usuario = $this->v25_usuario ";
       $virgula = ",";
       if(trim($this->v25_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "v25_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v25_datainc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v25_datainc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v25_datainc_dia"] !="") ){ 
       $sql  .= $virgula." v25_datainc = '$this->v25_datainc' ";
       $virgula = ",";
       if(trim($this->v25_datainc) == null ){ 
         $this->erro_sql = " Campo Data de Inclusão nao Informado.";
         $this->erro_campo = "v25_datainc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v25_datainc_dia"])){ 
         $sql  .= $virgula." v25_datainc = null ";
         $virgula = ",";
         if(trim($this->v25_datainc) == null ){ 
           $this->erro_sql = " Campo Data de Inclusão nao Informado.";
           $this->erro_campo = "v25_datainc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v25_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v25_hora"])){ 
       $sql  .= $virgula." v25_hora = '$this->v25_hora' ";
       $virgula = ",";
       if(trim($this->v25_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "v25_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v25_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v25_numero"])){ 
       $sql  .= $virgula." v25_numero = $this->v25_numero ";
       $virgula = ",";
       if(trim($this->v25_numero) == null ){ 
         $this->erro_sql = " Campo Livro nao Informado.";
         $this->erro_campo = "v25_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v25_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v25_instit"])){ 
       $sql  .= $virgula." v25_instit = $this->v25_instit ";
       $virgula = ",";
       if(trim($this->v25_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "v25_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v25_tipolivro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v25_tipolivro"])){ 
       $sql  .= $virgula." v25_tipolivro = $this->v25_tipolivro ";
       $virgula = ",";
       if(trim($this->v25_tipolivro) == null ){ 
         $this->erro_sql = " Campo Tipo do Livro nao Informado.";
         $this->erro_campo = "v25_tipolivro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v25_sequencial!=null){
       $sql .= " v25_sequencial = $this->v25_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v25_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14833,'$this->v25_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v25_sequencial"]) || $this->v25_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2611,14833,'".AddSlashes(pg_result($resaco,$conresaco,'v25_sequencial'))."','$this->v25_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v25_usuario"]) || $this->v25_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2611,14834,'".AddSlashes(pg_result($resaco,$conresaco,'v25_usuario'))."','$this->v25_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v25_datainc"]) || $this->v25_datainc != "")
           $resac = db_query("insert into db_acount values($acount,2611,14835,'".AddSlashes(pg_result($resaco,$conresaco,'v25_datainc'))."','$this->v25_datainc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v25_hora"]) || $this->v25_hora != "")
           $resac = db_query("insert into db_acount values($acount,2611,14836,'".AddSlashes(pg_result($resaco,$conresaco,'v25_hora'))."','$this->v25_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v25_numero"]) || $this->v25_numero != "")
           $resac = db_query("insert into db_acount values($acount,2611,14837,'".AddSlashes(pg_result($resaco,$conresaco,'v25_numero'))."','$this->v25_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v25_instit"]) || $this->v25_instit != "")
           $resac = db_query("insert into db_acount values($acount,2611,14856,'".AddSlashes(pg_result($resaco,$conresaco,'v25_instit'))."','$this->v25_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v25_tipolivro"]) || $this->v25_tipolivro != "")
           $resac = db_query("insert into db_acount values($acount,2611,14857,'".AddSlashes(pg_result($resaco,$conresaco,'v25_tipolivro'))."','$this->v25_tipolivro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Livro de CDA nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v25_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Livro de CDA nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v25_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v25_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v25_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v25_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14833,'$v25_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2611,14833,'','".AddSlashes(pg_result($resaco,$iresaco,'v25_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2611,14834,'','".AddSlashes(pg_result($resaco,$iresaco,'v25_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2611,14835,'','".AddSlashes(pg_result($resaco,$iresaco,'v25_datainc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2611,14836,'','".AddSlashes(pg_result($resaco,$iresaco,'v25_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2611,14837,'','".AddSlashes(pg_result($resaco,$iresaco,'v25_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2611,14856,'','".AddSlashes(pg_result($resaco,$iresaco,'v25_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2611,14857,'','".AddSlashes(pg_result($resaco,$iresaco,'v25_tipolivro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from certidlivro
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v25_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v25_sequencial = $v25_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Livro de CDA nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v25_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Livro de CDA nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v25_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v25_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:certidlivro";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v25_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from certidlivro ";
     $sql .= "      inner join db_config  on  db_config.codigo = certidlivro.v25_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = certidlivro.v25_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($v25_sequencial!=null ){
         $sql2 .= " where certidlivro.v25_sequencial = $v25_sequencial "; 
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
   function sql_query_file ( $v25_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from certidlivro ";
     $sql2 = "";
     if($dbwhere==""){
       if($v25_sequencial!=null ){
         $sql2 .= " where certidlivro.v25_sequencial = $v25_sequencial "; 
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
  
 function sql_query_livro ( $v25_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
    $sql .= " from certid ";
    $sql .= "       left join certdiv           on certid.v13_certid           = certdiv.v14_certid ";
    $sql .= "       left join certter           on certid.v13_certid           = certter.v14_certid ";
    $sql .= "       left join divida            on certdiv.v14_coddiv          = divida.v01_coddiv ";
    $sql .= "       left join termo             on certter.v14_parcel          = termo.v07_parcel ";
    $sql .= "       left join cgm cgmtermo      on termo.v07_numcgm            = cgmtermo.z01_numcgm ";
    $sql .= "       left join cgm cgmdivida     on divida.v01_numcgm           = cgmdivida.z01_numcgm ";
    $sql .= "       left join certidlivrofolha  on certid.v13_certid           = certidlivrofolha.v26_certid";
    $sql2 = "";
    if($dbwhere==""){
     if($v25_sequencial!=null ){
         $sql2 .= " where certidlivro.v25_sequencial = $v25_sequencial "; 
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