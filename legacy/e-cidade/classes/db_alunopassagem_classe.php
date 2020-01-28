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

//MODULO: educação
//CLASSE DA ENTIDADE alunopassagem
class cl_alunopassagem { 
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
   var $ed215_i_codigo = 0; 
   var $ed215_i_aluno = 0; 
   var $ed215_i_linha = 0; 
   var $ed215_i_usuario = 0; 
   var $ed215_i_ano = 0; 
   var $ed215_d_datacad_dia = null; 
   var $ed215_d_datacad_mes = null; 
   var $ed215_d_datacad_ano = null; 
   var $ed215_d_datacad = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed215_i_codigo = int4 = Código 
                 ed215_i_aluno = int4 = Aluno 
                 ed215_i_linha = int4 = Código linha 
                 ed215_i_usuario = int4 = Usuario 
                 ed215_i_ano = int4 = Código ano 
                 ed215_d_datacad = date = Data cadastro 
                 ";
   //funcao construtor da classe 
   function cl_alunopassagem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("alunopassagem"); 
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
       $this->ed215_i_codigo = ($this->ed215_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed215_i_codigo"]:$this->ed215_i_codigo);
       $this->ed215_i_aluno = ($this->ed215_i_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed215_i_aluno"]:$this->ed215_i_aluno);
       $this->ed215_i_linha = ($this->ed215_i_linha == ""?@$GLOBALS["HTTP_POST_VARS"]["ed215_i_linha"]:$this->ed215_i_linha);
       $this->ed215_i_usuario = ($this->ed215_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed215_i_usuario"]:$this->ed215_i_usuario);
       $this->ed215_i_ano = ($this->ed215_i_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed215_i_ano"]:$this->ed215_i_ano);
       if($this->ed215_d_datacad == ""){
         $this->ed215_d_datacad_dia = ($this->ed215_d_datacad_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed215_d_datacad_dia"]:$this->ed215_d_datacad_dia);
         $this->ed215_d_datacad_mes = ($this->ed215_d_datacad_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed215_d_datacad_mes"]:$this->ed215_d_datacad_mes);
         $this->ed215_d_datacad_ano = ($this->ed215_d_datacad_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed215_d_datacad_ano"]:$this->ed215_d_datacad_ano);
         if($this->ed215_d_datacad_dia != ""){
            $this->ed215_d_datacad = $this->ed215_d_datacad_ano."-".$this->ed215_d_datacad_mes."-".$this->ed215_d_datacad_dia;
         }
       }
     }else{
       $this->ed215_i_codigo = ($this->ed215_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed215_i_codigo"]:$this->ed215_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed215_i_codigo){ 
      $this->atualizacampos();
     if($this->ed215_i_aluno == null ){ 
       $this->erro_sql = " Campo Aluno nao Informado.";
       $this->erro_campo = "ed215_i_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed215_i_linha == null ){ 
       $this->erro_sql = " Campo Código linha nao Informado.";
       $this->erro_campo = "ed215_i_linha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed215_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuario nao Informado.";
       $this->erro_campo = "ed215_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed215_i_ano == null ){ 
       $this->erro_sql = " Campo Código ano nao Informado.";
       $this->erro_campo = "ed215_i_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed215_d_datacad == null ){ 
       $this->erro_sql = " Campo Data cadastro nao Informado.";
       $this->erro_campo = "ed215_d_datacad_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed215_i_codigo == "" || $ed215_i_codigo == null ){
       $result = db_query("select nextval('alunopassagem_ed215_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: alunopassagem_ed215_i_codigo_seq do campo: ed215_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed215_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from alunopassagem_ed215_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed215_i_codigo)){
         $this->erro_sql = " Campo ed215_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed215_i_codigo = $ed215_i_codigo; 
       }
     }
     if(($this->ed215_i_codigo == null) || ($this->ed215_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed215_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into alunopassagem(
                                       ed215_i_codigo 
                                      ,ed215_i_aluno 
                                      ,ed215_i_linha 
                                      ,ed215_i_usuario 
                                      ,ed215_i_ano 
                                      ,ed215_d_datacad 
                       )
                values (
                                $this->ed215_i_codigo 
                               ,$this->ed215_i_aluno 
                               ,$this->ed215_i_linha 
                               ,$this->ed215_i_usuario 
                               ,$this->ed215_i_ano 
                               ,".($this->ed215_d_datacad == "null" || $this->ed215_d_datacad == ""?"null":"'".$this->ed215_d_datacad."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "alunopassagem ($this->ed215_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "alunopassagem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "alunopassagem ($this->ed215_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed215_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed215_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11174,'$this->ed215_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1930,11174,'','".AddSlashes(pg_result($resaco,0,'ed215_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1930,11180,'','".AddSlashes(pg_result($resaco,0,'ed215_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1930,11347,'','".AddSlashes(pg_result($resaco,0,'ed215_i_linha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1930,11179,'','".AddSlashes(pg_result($resaco,0,'ed215_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1930,11348,'','".AddSlashes(pg_result($resaco,0,'ed215_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1930,11178,'','".AddSlashes(pg_result($resaco,0,'ed215_d_datacad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed215_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update alunopassagem set ";
     $virgula = "";
     if(trim($this->ed215_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed215_i_codigo"])){ 
       $sql  .= $virgula." ed215_i_codigo = $this->ed215_i_codigo ";
       $virgula = ",";
       if(trim($this->ed215_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed215_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed215_i_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed215_i_aluno"])){ 
       $sql  .= $virgula." ed215_i_aluno = $this->ed215_i_aluno ";
       $virgula = ",";
       if(trim($this->ed215_i_aluno) == null ){ 
         $this->erro_sql = " Campo Aluno nao Informado.";
         $this->erro_campo = "ed215_i_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed215_i_linha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed215_i_linha"])){ 
       $sql  .= $virgula." ed215_i_linha = $this->ed215_i_linha ";
       $virgula = ",";
       if(trim($this->ed215_i_linha) == null ){ 
         $this->erro_sql = " Campo Código linha nao Informado.";
         $this->erro_campo = "ed215_i_linha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed215_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed215_i_usuario"])){ 
       $sql  .= $virgula." ed215_i_usuario = $this->ed215_i_usuario ";
       $virgula = ",";
       if(trim($this->ed215_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuario nao Informado.";
         $this->erro_campo = "ed215_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed215_i_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed215_i_ano"])){ 
       $sql  .= $virgula." ed215_i_ano = $this->ed215_i_ano ";
       $virgula = ",";
       if(trim($this->ed215_i_ano) == null ){ 
         $this->erro_sql = " Campo Código ano nao Informado.";
         $this->erro_campo = "ed215_i_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed215_d_datacad)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed215_d_datacad_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed215_d_datacad_dia"] !="") ){ 
       $sql  .= $virgula." ed215_d_datacad = '$this->ed215_d_datacad' ";
       $virgula = ",";
       if(trim($this->ed215_d_datacad) == null ){ 
         $this->erro_sql = " Campo Data cadastro nao Informado.";
         $this->erro_campo = "ed215_d_datacad_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed215_d_datacad_dia"])){ 
         $sql  .= $virgula." ed215_d_datacad = null ";
         $virgula = ",";
         if(trim($this->ed215_d_datacad) == null ){ 
           $this->erro_sql = " Campo Data cadastro nao Informado.";
           $this->erro_campo = "ed215_d_datacad_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($ed215_i_codigo!=null){
       $sql .= " ed215_i_codigo = $this->ed215_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed215_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11174,'$this->ed215_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed215_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1930,11174,'".AddSlashes(pg_result($resaco,$conresaco,'ed215_i_codigo'))."','$this->ed215_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed215_i_aluno"]))
           $resac = db_query("insert into db_acount values($acount,1930,11180,'".AddSlashes(pg_result($resaco,$conresaco,'ed215_i_aluno'))."','$this->ed215_i_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed215_i_linha"]))
           $resac = db_query("insert into db_acount values($acount,1930,11347,'".AddSlashes(pg_result($resaco,$conresaco,'ed215_i_linha'))."','$this->ed215_i_linha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed215_i_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1930,11179,'".AddSlashes(pg_result($resaco,$conresaco,'ed215_i_usuario'))."','$this->ed215_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed215_i_ano"]))
           $resac = db_query("insert into db_acount values($acount,1930,11348,'".AddSlashes(pg_result($resaco,$conresaco,'ed215_i_ano'))."','$this->ed215_i_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed215_d_datacad"]))
           $resac = db_query("insert into db_acount values($acount,1930,11178,'".AddSlashes(pg_result($resaco,$conresaco,'ed215_d_datacad'))."','$this->ed215_d_datacad',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "alunopassagem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed215_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "alunopassagem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed215_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed215_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed215_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed215_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11174,'$ed215_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1930,11174,'','".AddSlashes(pg_result($resaco,$iresaco,'ed215_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1930,11180,'','".AddSlashes(pg_result($resaco,$iresaco,'ed215_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1930,11347,'','".AddSlashes(pg_result($resaco,$iresaco,'ed215_i_linha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1930,11179,'','".AddSlashes(pg_result($resaco,$iresaco,'ed215_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1930,11348,'','".AddSlashes(pg_result($resaco,$iresaco,'ed215_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1930,11178,'','".AddSlashes(pg_result($resaco,$iresaco,'ed215_d_datacad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from alunopassagem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed215_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed215_i_codigo = $ed215_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "alunopassagem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed215_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "alunopassagem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed215_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed215_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:alunopassagem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed215_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunopassagem ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = alunopassagem.ed215_i_usuario";
     $sql .= "      inner join linha  on  linha.ed217_i_codigo = alunopassagem.ed215_i_linha";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = alunopassagem.ed215_i_aluno";
     $sql .= "      inner join tipolinha  on  tipolinha.ed226_i_codigo = linha.ed217_i_tipolinha";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = aluno.ed47_i_pais";
     $sql .= "      left join alunocurso  on  alunocurso.ed56_i_aluno = aluno.ed47_i_codigo";
     $sql .= "      left join escola  on  escola.ed18_i_codigo = alunocurso.ed56_i_escola";
     $sql .= "      left join alunofora  on  alunofora.ed216_i_aluno = aluno.ed47_i_codigo";
     $sql .= "      left join escolaproc  on escolaproc.ed82_i_codigo = alunofora.ed216_i_escolaproc";
     
     $sql2 = "";
     if($dbwhere==""){
       if($ed215_i_codigo!=null ){
         $sql2 .= " where alunopassagem.ed215_i_codigo = $ed215_i_codigo "; 
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
   function sql_query_file ( $ed215_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunopassagem ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed215_i_codigo!=null ){
         $sql2 .= " where alunopassagem.ed215_i_codigo = $ed215_i_codigo "; 
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