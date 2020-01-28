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

//MODULO: saude
//CLASSE DA ENTIDADE sau_procincremento
class cl_sau_procincremento { 
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
   var $sd79_i_codigo = 0; 
   var $sd79_i_procedimento = 0; 
   var $sd79_i_habilitacao = 0; 
   var $sd79_f_sh = 0; 
   var $sd79_f_sa = 0; 
   var $sd79_f_sp = 0; 
   var $sd79_i_anocomp = 0; 
   var $sd79_i_mescomp = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd79_i_codigo = int8 = Codigo 
                 sd79_i_procedimento = int8 = Procedimento 
                 sd79_i_habilitacao = int8 = Habilitação 
                 sd79_f_sh = float8 = Valor do Serviço Hospitalar 
                 sd79_f_sa = float8 = Valor do Serviço Ambulatorial 
                 sd79_f_sp = float8 = Valor do Serviço Profissional 
                 sd79_i_anocomp = int4 = Ano 
                 sd79_i_mescomp = int4 = Mes 
                 ";
   //funcao construtor da classe 
   function cl_sau_procincremento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_procincremento"); 
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
       $this->sd79_i_codigo = ($this->sd79_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd79_i_codigo"]:$this->sd79_i_codigo);
       $this->sd79_i_procedimento = ($this->sd79_i_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd79_i_procedimento"]:$this->sd79_i_procedimento);
       $this->sd79_i_habilitacao = ($this->sd79_i_habilitacao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd79_i_habilitacao"]:$this->sd79_i_habilitacao);
       $this->sd79_f_sh = ($this->sd79_f_sh == ""?@$GLOBALS["HTTP_POST_VARS"]["sd79_f_sh"]:$this->sd79_f_sh);
       $this->sd79_f_sa = ($this->sd79_f_sa == ""?@$GLOBALS["HTTP_POST_VARS"]["sd79_f_sa"]:$this->sd79_f_sa);
       $this->sd79_f_sp = ($this->sd79_f_sp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd79_f_sp"]:$this->sd79_f_sp);
       $this->sd79_i_anocomp = ($this->sd79_i_anocomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd79_i_anocomp"]:$this->sd79_i_anocomp);
       $this->sd79_i_mescomp = ($this->sd79_i_mescomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd79_i_mescomp"]:$this->sd79_i_mescomp);
     }else{
       $this->sd79_i_codigo = ($this->sd79_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd79_i_codigo"]:$this->sd79_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd79_i_codigo){ 
      $this->atualizacampos();
     if($this->sd79_i_procedimento == null ){ 
       $this->erro_sql = " Campo Procedimento nao Informado.";
       $this->erro_campo = "sd79_i_procedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd79_i_habilitacao == null ){ 
       $this->erro_sql = " Campo Habilitação nao Informado.";
       $this->erro_campo = "sd79_i_habilitacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd79_f_sh == null ){ 
       $this->erro_sql = " Campo Valor do Serviço Hospitalar nao Informado.";
       $this->erro_campo = "sd79_f_sh";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd79_f_sa == null ){ 
       $this->erro_sql = " Campo Valor do Serviço Ambulatorial nao Informado.";
       $this->erro_campo = "sd79_f_sa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd79_f_sp == null ){ 
       $this->erro_sql = " Campo Valor do Serviço Profissional nao Informado.";
       $this->erro_campo = "sd79_f_sp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd79_i_anocomp == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "sd79_i_anocomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd79_i_mescomp == null ){ 
       $this->erro_sql = " Campo Mes nao Informado.";
       $this->erro_campo = "sd79_i_mescomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd79_i_codigo == "" || $sd79_i_codigo == null ){
       $result = db_query("select nextval('sau_procinremento_sd79_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_procinremento_sd79_i_codigo_seq do campo: sd79_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd79_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_procinremento_sd79_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd79_i_codigo)){
         $this->erro_sql = " Campo sd79_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd79_i_codigo = $sd79_i_codigo; 
       }
     }
     if(($this->sd79_i_codigo == null) || ($this->sd79_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd79_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_procincremento(
                                       sd79_i_codigo 
                                      ,sd79_i_procedimento 
                                      ,sd79_i_habilitacao 
                                      ,sd79_f_sh 
                                      ,sd79_f_sa 
                                      ,sd79_f_sp 
                                      ,sd79_i_anocomp 
                                      ,sd79_i_mescomp 
                       )
                values (
                                $this->sd79_i_codigo 
                               ,$this->sd79_i_procedimento 
                               ,$this->sd79_i_habilitacao 
                               ,$this->sd79_f_sh 
                               ,$this->sd79_f_sa 
                               ,$this->sd79_f_sp 
                               ,$this->sd79_i_anocomp 
                               ,$this->sd79_i_mescomp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Incremento de Procedimento ($this->sd79_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Incremento de Procedimento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Incremento de Procedimento ($this->sd79_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd79_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd79_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11576,'$this->sd79_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2007,11576,'','".AddSlashes(pg_result($resaco,0,'sd79_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2007,11571,'','".AddSlashes(pg_result($resaco,0,'sd79_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2007,11579,'','".AddSlashes(pg_result($resaco,0,'sd79_i_habilitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2007,11580,'','".AddSlashes(pg_result($resaco,0,'sd79_f_sh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2007,11581,'','".AddSlashes(pg_result($resaco,0,'sd79_f_sa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2007,11582,'','".AddSlashes(pg_result($resaco,0,'sd79_f_sp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2007,11583,'','".AddSlashes(pg_result($resaco,0,'sd79_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2007,11584,'','".AddSlashes(pg_result($resaco,0,'sd79_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd79_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_procincremento set ";
     $virgula = "";
     if(trim($this->sd79_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd79_i_codigo"])){ 
       $sql  .= $virgula." sd79_i_codigo = $this->sd79_i_codigo ";
       $virgula = ",";
       if(trim($this->sd79_i_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "sd79_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd79_i_procedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd79_i_procedimento"])){ 
       $sql  .= $virgula." sd79_i_procedimento = $this->sd79_i_procedimento ";
       $virgula = ",";
       if(trim($this->sd79_i_procedimento) == null ){ 
         $this->erro_sql = " Campo Procedimento nao Informado.";
         $this->erro_campo = "sd79_i_procedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd79_i_habilitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd79_i_habilitacao"])){ 
       $sql  .= $virgula." sd79_i_habilitacao = $this->sd79_i_habilitacao ";
       $virgula = ",";
       if(trim($this->sd79_i_habilitacao) == null ){ 
         $this->erro_sql = " Campo Habilitação nao Informado.";
         $this->erro_campo = "sd79_i_habilitacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd79_f_sh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd79_f_sh"])){ 
       $sql  .= $virgula." sd79_f_sh = $this->sd79_f_sh ";
       $virgula = ",";
       if(trim($this->sd79_f_sh) == null ){ 
         $this->erro_sql = " Campo Valor do Serviço Hospitalar nao Informado.";
         $this->erro_campo = "sd79_f_sh";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd79_f_sa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd79_f_sa"])){ 
       $sql  .= $virgula." sd79_f_sa = $this->sd79_f_sa ";
       $virgula = ",";
       if(trim($this->sd79_f_sa) == null ){ 
         $this->erro_sql = " Campo Valor do Serviço Ambulatorial nao Informado.";
         $this->erro_campo = "sd79_f_sa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd79_f_sp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd79_f_sp"])){ 
       $sql  .= $virgula." sd79_f_sp = $this->sd79_f_sp ";
       $virgula = ",";
       if(trim($this->sd79_f_sp) == null ){ 
         $this->erro_sql = " Campo Valor do Serviço Profissional nao Informado.";
         $this->erro_campo = "sd79_f_sp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd79_i_anocomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd79_i_anocomp"])){ 
       $sql  .= $virgula." sd79_i_anocomp = $this->sd79_i_anocomp ";
       $virgula = ",";
       if(trim($this->sd79_i_anocomp) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "sd79_i_anocomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd79_i_mescomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd79_i_mescomp"])){ 
       $sql  .= $virgula." sd79_i_mescomp = $this->sd79_i_mescomp ";
       $virgula = ",";
       if(trim($this->sd79_i_mescomp) == null ){ 
         $this->erro_sql = " Campo Mes nao Informado.";
         $this->erro_campo = "sd79_i_mescomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd79_i_codigo!=null){
       $sql .= " sd79_i_codigo = $this->sd79_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd79_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11576,'$this->sd79_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd79_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2007,11576,'".AddSlashes(pg_result($resaco,$conresaco,'sd79_i_codigo'))."','$this->sd79_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd79_i_procedimento"]))
           $resac = db_query("insert into db_acount values($acount,2007,11571,'".AddSlashes(pg_result($resaco,$conresaco,'sd79_i_procedimento'))."','$this->sd79_i_procedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd79_i_habilitacao"]))
           $resac = db_query("insert into db_acount values($acount,2007,11579,'".AddSlashes(pg_result($resaco,$conresaco,'sd79_i_habilitacao'))."','$this->sd79_i_habilitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd79_f_sh"]))
           $resac = db_query("insert into db_acount values($acount,2007,11580,'".AddSlashes(pg_result($resaco,$conresaco,'sd79_f_sh'))."','$this->sd79_f_sh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd79_f_sa"]))
           $resac = db_query("insert into db_acount values($acount,2007,11581,'".AddSlashes(pg_result($resaco,$conresaco,'sd79_f_sa'))."','$this->sd79_f_sa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd79_f_sp"]))
           $resac = db_query("insert into db_acount values($acount,2007,11582,'".AddSlashes(pg_result($resaco,$conresaco,'sd79_f_sp'))."','$this->sd79_f_sp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd79_i_anocomp"]))
           $resac = db_query("insert into db_acount values($acount,2007,11583,'".AddSlashes(pg_result($resaco,$conresaco,'sd79_i_anocomp'))."','$this->sd79_i_anocomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd79_i_mescomp"]))
           $resac = db_query("insert into db_acount values($acount,2007,11584,'".AddSlashes(pg_result($resaco,$conresaco,'sd79_i_mescomp'))."','$this->sd79_i_mescomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Incremento de Procedimento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd79_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Incremento de Procedimento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd79_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd79_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd79_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd79_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11576,'$sd79_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2007,11576,'','".AddSlashes(pg_result($resaco,$iresaco,'sd79_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2007,11571,'','".AddSlashes(pg_result($resaco,$iresaco,'sd79_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2007,11579,'','".AddSlashes(pg_result($resaco,$iresaco,'sd79_i_habilitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2007,11580,'','".AddSlashes(pg_result($resaco,$iresaco,'sd79_f_sh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2007,11581,'','".AddSlashes(pg_result($resaco,$iresaco,'sd79_f_sa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2007,11582,'','".AddSlashes(pg_result($resaco,$iresaco,'sd79_f_sp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2007,11583,'','".AddSlashes(pg_result($resaco,$iresaco,'sd79_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2007,11584,'','".AddSlashes(pg_result($resaco,$iresaco,'sd79_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_procincremento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd79_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd79_i_codigo = $sd79_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Incremento de Procedimento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd79_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Incremento de Procedimento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd79_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd79_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_procincremento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd79_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_procincremento ";
     $sql .= "      inner join sau_habilitacao  on  sau_habilitacao.sd75_i_codigo = sau_procincremento.sd79_i_habilitacao";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = sau_procincremento.sd79_i_procedimento";
     $sql .= "      left join sau_financiamento  on  sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento";
     $sql .= "      left join sau_rubrica  on  sau_rubrica.sd64_i_codigo = sau_procedimento.sd63_i_rubrica";
     $sql .= "      left join sau_complexidade  on  sau_complexidade.sd69_i_codigo = sau_procedimento.sd63_i_complexidade";
     $sql2 = "";
     if($dbwhere==""){
       if($sd79_i_codigo!=null ){
         $sql2 .= " where sau_procincremento.sd79_i_codigo = $sd79_i_codigo ";
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
   function sql_query_file ( $sd79_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_procincremento ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd79_i_codigo!=null ){
         $sql2 .= " where sau_procincremento.sd79_i_codigo = $sd79_i_codigo ";
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