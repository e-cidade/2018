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

//MODULO: caixa
//CLASSE DA ENTIDADE debcontaarquivo
class cl_debcontaarquivo { 
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
   var $d72_codigo = 0; 
   var $d72_nsa = 0; 
   var $d72_tipo = 0; 
   var $d72_data_dia = null; 
   var $d72_data_mes = null; 
   var $d72_data_ano = null; 
   var $d72_data = null; 
   var $d72_hora = null; 
   var $d72_usuario = 0; 
   var $d72_nome = null; 
   var $d72_conteudo = null; 
   var $d72_numpar = 0; 
   var $d72_arretipo = 0; 
   var $d72_banco = 0; 
   var $d72_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d72_codigo = int4 = Codigo sequencial 
                 d72_nsa = int4 = NSA 
                 d72_tipo = int4 = Tipo 
                 d72_data = date = Data 
                 d72_hora = char(5) = Hora 
                 d72_usuario = int4 = Cod. Usuário 
                 d72_nome = varchar(100) = Nome do arquivo 
                 d72_conteudo = text = Conteudo do arquivo 
                 d72_numpar = int4 = Parcela 
                 d72_arretipo = int4 = tipo de debito 
                 d72_banco = int4 = codigo do banco 
                 d72_instit = int4 = Código da Instituição 
                 ";
   //funcao construtor da classe 
   function cl_debcontaarquivo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("debcontaarquivo"); 
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
       $this->d72_codigo = ($this->d72_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["d72_codigo"]:$this->d72_codigo);
       $this->d72_nsa = ($this->d72_nsa == ""?@$GLOBALS["HTTP_POST_VARS"]["d72_nsa"]:$this->d72_nsa);
       $this->d72_tipo = ($this->d72_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["d72_tipo"]:$this->d72_tipo);
       if($this->d72_data == ""){
         $this->d72_data_dia = ($this->d72_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["d72_data_dia"]:$this->d72_data_dia);
         $this->d72_data_mes = ($this->d72_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["d72_data_mes"]:$this->d72_data_mes);
         $this->d72_data_ano = ($this->d72_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["d72_data_ano"]:$this->d72_data_ano);
         if($this->d72_data_dia != ""){
            $this->d72_data = $this->d72_data_ano."-".$this->d72_data_mes."-".$this->d72_data_dia;
         }
       }
       $this->d72_hora = ($this->d72_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["d72_hora"]:$this->d72_hora);
       $this->d72_usuario = ($this->d72_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["d72_usuario"]:$this->d72_usuario);
       $this->d72_nome = ($this->d72_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["d72_nome"]:$this->d72_nome);
       $this->d72_conteudo = ($this->d72_conteudo == ""?@$GLOBALS["HTTP_POST_VARS"]["d72_conteudo"]:$this->d72_conteudo);
       $this->d72_numpar = ($this->d72_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["d72_numpar"]:$this->d72_numpar);
       $this->d72_arretipo = ($this->d72_arretipo == ""?@$GLOBALS["HTTP_POST_VARS"]["d72_arretipo"]:$this->d72_arretipo);
       $this->d72_banco = ($this->d72_banco == ""?@$GLOBALS["HTTP_POST_VARS"]["d72_banco"]:$this->d72_banco);
       $this->d72_instit = ($this->d72_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["d72_instit"]:$this->d72_instit);
     }else{
       $this->d72_codigo = ($this->d72_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["d72_codigo"]:$this->d72_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($d72_codigo){ 
      $this->atualizacampos();
     if($this->d72_nsa == null ){ 
       $this->d72_nsa = "0";
     }
     if($this->d72_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "d72_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d72_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "d72_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d72_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "d72_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d72_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "d72_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d72_nome == null ){ 
       $this->erro_sql = " Campo Nome do arquivo nao Informado.";
       $this->erro_campo = "d72_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d72_conteudo == null ){ 
       $this->erro_sql = " Campo Conteudo do arquivo nao Informado.";
       $this->erro_campo = "d72_conteudo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d72_numpar == null ){ 
       $this->d72_numpar = "0";
     }
     if($this->d72_arretipo == null ){ 
       $this->erro_sql = " Campo tipo de debito nao Informado.";
       $this->erro_campo = "d72_arretipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d72_banco == null ){ 
       $this->erro_sql = " Campo codigo do banco nao Informado.";
       $this->erro_campo = "d72_banco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d72_instit == null ){ 
       $this->erro_sql = " Campo Código da Instituição nao Informado.";
       $this->erro_campo = "d72_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($d72_codigo == "" || $d72_codigo == null ){
       $result = db_query("select nextval('debcontaarquivo_d72_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: debcontaarquivo_d72_codigo_seq do campo: d72_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->d72_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from debcontaarquivo_d72_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $d72_codigo)){
         $this->erro_sql = " Campo d72_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->d72_codigo = $d72_codigo; 
       }
     }
     if(($this->d72_codigo == null) || ($this->d72_codigo == "") ){ 
       $this->erro_sql = " Campo d72_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into debcontaarquivo(
                                       d72_codigo 
                                      ,d72_nsa 
                                      ,d72_tipo 
                                      ,d72_data 
                                      ,d72_hora 
                                      ,d72_usuario 
                                      ,d72_nome 
                                      ,d72_conteudo 
                                      ,d72_numpar 
                                      ,d72_arretipo 
                                      ,d72_banco 
                                      ,d72_instit 
                       )
                values (
                                $this->d72_codigo 
                               ,$this->d72_nsa 
                               ,$this->d72_tipo 
                               ,".($this->d72_data == "null" || $this->d72_data == ""?"null":"'".$this->d72_data."'")." 
                               ,'$this->d72_hora' 
                               ,$this->d72_usuario 
                               ,'$this->d72_nome' 
                               ,'$this->d72_conteudo' 
                               ,$this->d72_numpar 
                               ,$this->d72_arretipo 
                               ,$this->d72_banco 
                               ,$this->d72_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivo do debito em conta ($this->d72_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivo do debito em conta já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivo do debito em conta ($this->d72_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d72_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d72_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7971,'$this->d72_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1339,7971,'','".AddSlashes(pg_result($resaco,0,'d72_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1339,7972,'','".AddSlashes(pg_result($resaco,0,'d72_nsa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1339,7973,'','".AddSlashes(pg_result($resaco,0,'d72_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1339,7974,'','".AddSlashes(pg_result($resaco,0,'d72_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1339,7975,'','".AddSlashes(pg_result($resaco,0,'d72_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1339,7976,'','".AddSlashes(pg_result($resaco,0,'d72_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1339,7977,'','".AddSlashes(pg_result($resaco,0,'d72_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1339,7978,'','".AddSlashes(pg_result($resaco,0,'d72_conteudo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1339,8270,'','".AddSlashes(pg_result($resaco,0,'d72_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1339,8274,'','".AddSlashes(pg_result($resaco,0,'d72_arretipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1339,8275,'','".AddSlashes(pg_result($resaco,0,'d72_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1339,10752,'','".AddSlashes(pg_result($resaco,0,'d72_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($d72_codigo=null) { 
      $this->atualizacampos();
     $sql = " update debcontaarquivo set ";
     $virgula = "";
     if(trim($this->d72_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d72_codigo"])){ 
       $sql  .= $virgula." d72_codigo = $this->d72_codigo ";
       $virgula = ",";
       if(trim($this->d72_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "d72_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d72_nsa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d72_nsa"])){ 
        if(trim($this->d72_nsa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d72_nsa"])){ 
           $this->d72_nsa = "0" ; 
        } 
       $sql  .= $virgula." d72_nsa = $this->d72_nsa ";
       $virgula = ",";
     }
     if(trim($this->d72_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d72_tipo"])){ 
       $sql  .= $virgula." d72_tipo = $this->d72_tipo ";
       $virgula = ",";
       if(trim($this->d72_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "d72_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d72_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d72_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["d72_data_dia"] !="") ){ 
       $sql  .= $virgula." d72_data = '$this->d72_data' ";
       $virgula = ",";
       if(trim($this->d72_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "d72_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["d72_data_dia"])){ 
         $sql  .= $virgula." d72_data = null ";
         $virgula = ",";
         if(trim($this->d72_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "d72_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->d72_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d72_hora"])){ 
       $sql  .= $virgula." d72_hora = '$this->d72_hora' ";
       $virgula = ",";
       if(trim($this->d72_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "d72_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d72_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d72_usuario"])){ 
       $sql  .= $virgula." d72_usuario = $this->d72_usuario ";
       $virgula = ",";
       if(trim($this->d72_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "d72_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d72_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d72_nome"])){ 
       $sql  .= $virgula." d72_nome = '$this->d72_nome' ";
       $virgula = ",";
       if(trim($this->d72_nome) == null ){ 
         $this->erro_sql = " Campo Nome do arquivo nao Informado.";
         $this->erro_campo = "d72_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d72_conteudo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d72_conteudo"])){ 
       $sql  .= $virgula." d72_conteudo = '$this->d72_conteudo' ";
       $virgula = ",";
       if(trim($this->d72_conteudo) == null ){ 
         $this->erro_sql = " Campo Conteudo do arquivo nao Informado.";
         $this->erro_campo = "d72_conteudo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d72_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d72_numpar"])){ 
        if(trim($this->d72_numpar)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d72_numpar"])){ 
           $this->d72_numpar = "0" ; 
        } 
       $sql  .= $virgula." d72_numpar = $this->d72_numpar ";
       $virgula = ",";
     }
     if(trim($this->d72_arretipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d72_arretipo"])){ 
       $sql  .= $virgula." d72_arretipo = $this->d72_arretipo ";
       $virgula = ",";
       if(trim($this->d72_arretipo) == null ){ 
         $this->erro_sql = " Campo tipo de debito nao Informado.";
         $this->erro_campo = "d72_arretipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d72_banco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d72_banco"])){ 
       $sql  .= $virgula." d72_banco = $this->d72_banco ";
       $virgula = ",";
       if(trim($this->d72_banco) == null ){ 
         $this->erro_sql = " Campo codigo do banco nao Informado.";
         $this->erro_campo = "d72_banco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d72_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d72_instit"])){ 
       $sql  .= $virgula." d72_instit = $this->d72_instit ";
       $virgula = ",";
       if(trim($this->d72_instit) == null ){ 
         $this->erro_sql = " Campo Código da Instituição nao Informado.";
         $this->erro_campo = "d72_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($d72_codigo!=null){
       $sql .= " d72_codigo = $this->d72_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d72_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7971,'$this->d72_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d72_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1339,7971,'".AddSlashes(pg_result($resaco,$conresaco,'d72_codigo'))."','$this->d72_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d72_nsa"]))
           $resac = db_query("insert into db_acount values($acount,1339,7972,'".AddSlashes(pg_result($resaco,$conresaco,'d72_nsa'))."','$this->d72_nsa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d72_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1339,7973,'".AddSlashes(pg_result($resaco,$conresaco,'d72_tipo'))."','$this->d72_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d72_data"]))
           $resac = db_query("insert into db_acount values($acount,1339,7974,'".AddSlashes(pg_result($resaco,$conresaco,'d72_data'))."','$this->d72_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d72_hora"]))
           $resac = db_query("insert into db_acount values($acount,1339,7975,'".AddSlashes(pg_result($resaco,$conresaco,'d72_hora'))."','$this->d72_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d72_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1339,7976,'".AddSlashes(pg_result($resaco,$conresaco,'d72_usuario'))."','$this->d72_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d72_nome"]))
           $resac = db_query("insert into db_acount values($acount,1339,7977,'".AddSlashes(pg_result($resaco,$conresaco,'d72_nome'))."','$this->d72_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d72_conteudo"]))
           $resac = db_query("insert into db_acount values($acount,1339,7978,'".AddSlashes(pg_result($resaco,$conresaco,'d72_conteudo'))."','$this->d72_conteudo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d72_numpar"]))
           $resac = db_query("insert into db_acount values($acount,1339,8270,'".AddSlashes(pg_result($resaco,$conresaco,'d72_numpar'))."','$this->d72_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d72_arretipo"]))
           $resac = db_query("insert into db_acount values($acount,1339,8274,'".AddSlashes(pg_result($resaco,$conresaco,'d72_arretipo'))."','$this->d72_arretipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d72_banco"]))
           $resac = db_query("insert into db_acount values($acount,1339,8275,'".AddSlashes(pg_result($resaco,$conresaco,'d72_banco'))."','$this->d72_banco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d72_instit"]))
           $resac = db_query("insert into db_acount values($acount,1339,10752,'".AddSlashes(pg_result($resaco,$conresaco,'d72_instit'))."','$this->d72_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo do debito em conta nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d72_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo do debito em conta nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d72_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d72_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($d72_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d72_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7971,'$d72_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1339,7971,'','".AddSlashes(pg_result($resaco,$iresaco,'d72_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1339,7972,'','".AddSlashes(pg_result($resaco,$iresaco,'d72_nsa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1339,7973,'','".AddSlashes(pg_result($resaco,$iresaco,'d72_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1339,7974,'','".AddSlashes(pg_result($resaco,$iresaco,'d72_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1339,7975,'','".AddSlashes(pg_result($resaco,$iresaco,'d72_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1339,7976,'','".AddSlashes(pg_result($resaco,$iresaco,'d72_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1339,7977,'','".AddSlashes(pg_result($resaco,$iresaco,'d72_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1339,7978,'','".AddSlashes(pg_result($resaco,$iresaco,'d72_conteudo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1339,8270,'','".AddSlashes(pg_result($resaco,$iresaco,'d72_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1339,8274,'','".AddSlashes(pg_result($resaco,$iresaco,'d72_arretipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1339,8275,'','".AddSlashes(pg_result($resaco,$iresaco,'d72_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1339,10752,'','".AddSlashes(pg_result($resaco,$iresaco,'d72_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from debcontaarquivo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d72_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d72_codigo = $d72_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo do debito em conta nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d72_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo do debito em conta nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d72_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d72_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:debcontaarquivo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $d72_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from debcontaarquivo ";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = debcontaarquivo.d72_arretipo";
     $sql .= "      inner join db_config  on  db_config.codigo = debcontaarquivo.d72_instit";
     $sql .= "      inner join bancos  on  bancos.codbco = debcontaarquivo.d72_banco";
     $sql .= "      inner join db_config  as a on   a.codigo = arretipo.k00_instit";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($d72_codigo!=null ){
         $sql2 .= " where debcontaarquivo.d72_codigo = $d72_codigo "; 
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
   function sql_query_file ( $d72_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from debcontaarquivo ";
     $sql2 = "";
     if($dbwhere==""){
       if($d72_codigo!=null ){
         $sql2 .= " where debcontaarquivo.d72_codigo = $d72_codigo "; 
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
   function sql_query_tipo ( $d72_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from debcontaarquivo ";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = debcontaarquivo.d72_arretipo";
     $sql .= "      inner join bancos  on  bancos.codbco = debcontaarquivo.d72_banco";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
		 $sql .= "      left join debcontaarquivotipo on debcontaarquivotipo.d79_codigo = debcontaarquivo.d72_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($d72_codigo!=null ){
         $sql2 .= " where debcontaarquivo.d72_codigo = $d72_codigo "; 
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