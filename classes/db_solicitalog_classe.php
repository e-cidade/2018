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
//CLASSE DA ENTIDADE solicitalog
class cl_solicitalog { 
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
   var $pc15_codigo = 0; 
   var $pc15_numsol = 0; 
   var $pc15_codproc = 0; 
   var $pc15_codliclicita = 0; 
   var $pc15_solicitem = 0; 
   var $pc15_quant = 0; 
   var $pc15_vlrun = 0; 
   var $pc15_id_usuario = 0; 
   var $pc15_data_dia = null; 
   var $pc15_data_mes = null; 
   var $pc15_data_ano = null; 
   var $pc15_data = null; 
   var $pc15_hora = null; 
   var $pc15_opcao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc15_codigo = int8 = Codigo 
                 pc15_numsol = int8 = Solicitação 
                 pc15_codproc = int8 = Processo de compras 
                 pc15_codliclicita = int8 = Licitação 
                 pc15_solicitem = int8 = Item 
                 pc15_quant = float8 = Quantidade 
                 pc15_vlrun = float8 = Valor unitário 
                 pc15_id_usuario = int4 = Usuário 
                 pc15_data = date = Data 
                 pc15_hora = char(5) = Hora 
                 pc15_opcao = int4 = Opção 
                 ";
   //funcao construtor da classe 
   function cl_solicitalog() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("solicitalog"); 
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
       $this->pc15_codigo = ($this->pc15_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc15_codigo"]:$this->pc15_codigo);
       $this->pc15_numsol = ($this->pc15_numsol == ""?@$GLOBALS["HTTP_POST_VARS"]["pc15_numsol"]:$this->pc15_numsol);
       $this->pc15_codproc = ($this->pc15_codproc == ""?@$GLOBALS["HTTP_POST_VARS"]["pc15_codproc"]:$this->pc15_codproc);
       $this->pc15_codliclicita = ($this->pc15_codliclicita == ""?@$GLOBALS["HTTP_POST_VARS"]["pc15_codliclicita"]:$this->pc15_codliclicita);
       $this->pc15_solicitem = ($this->pc15_solicitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc15_solicitem"]:$this->pc15_solicitem);
       $this->pc15_quant = ($this->pc15_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["pc15_quant"]:$this->pc15_quant);
       $this->pc15_vlrun = ($this->pc15_vlrun == ""?@$GLOBALS["HTTP_POST_VARS"]["pc15_vlrun"]:$this->pc15_vlrun);
       $this->pc15_id_usuario = ($this->pc15_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["pc15_id_usuario"]:$this->pc15_id_usuario);
       if($this->pc15_data == ""){
         $this->pc15_data_dia = ($this->pc15_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc15_data_dia"]:$this->pc15_data_dia);
         $this->pc15_data_mes = ($this->pc15_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc15_data_mes"]:$this->pc15_data_mes);
         $this->pc15_data_ano = ($this->pc15_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc15_data_ano"]:$this->pc15_data_ano);
         if($this->pc15_data_dia != ""){
            $this->pc15_data = $this->pc15_data_ano."-".$this->pc15_data_mes."-".$this->pc15_data_dia;
         }
       }
       $this->pc15_hora = ($this->pc15_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["pc15_hora"]:$this->pc15_hora);
       $this->pc15_opcao = ($this->pc15_opcao == ""?@$GLOBALS["HTTP_POST_VARS"]["pc15_opcao"]:$this->pc15_opcao);
     }else{
       $this->pc15_codigo = ($this->pc15_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc15_codigo"]:$this->pc15_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($pc15_codigo){ 
      $this->atualizacampos();
     if($this->pc15_numsol == null ){ 
       $this->erro_sql = " Campo Solicitação nao Informado.";
       $this->erro_campo = "pc15_numsol";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc15_codproc == null ){ 
       $this->erro_sql = " Campo Processo de compras nao Informado.";
       $this->erro_campo = "pc15_codproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc15_codliclicita == null ){ 
       $this->pc15_codliclicita = "0";
     }
     if($this->pc15_solicitem == null ){ 
       $this->erro_sql = " Campo Item nao Informado.";
       $this->erro_campo = "pc15_solicitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc15_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "pc15_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc15_vlrun == null ){ 
       $this->erro_sql = " Campo Valor unitário nao Informado.";
       $this->erro_campo = "pc15_vlrun";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc15_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "pc15_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc15_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "pc15_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc15_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "pc15_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc15_opcao == null ){ 
       $this->erro_sql = " Campo Opção nao Informado.";
       $this->erro_campo = "pc15_opcao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc15_codigo == "" || $pc15_codigo == null ){
       $result = db_query("select nextval('solicitalog_pc15_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: solicitalog_pc15_codigo_seq do campo: pc15_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc15_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from solicitalog_pc15_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc15_codigo)){
         $this->erro_sql = " Campo pc15_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc15_codigo = $pc15_codigo; 
       }
     }
     if(($this->pc15_codigo == null) || ($this->pc15_codigo == "") ){ 
       $this->erro_sql = " Campo pc15_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into solicitalog(
                                       pc15_codigo 
                                      ,pc15_numsol 
                                      ,pc15_codproc 
                                      ,pc15_codliclicita 
                                      ,pc15_solicitem 
                                      ,pc15_quant 
                                      ,pc15_vlrun 
                                      ,pc15_id_usuario 
                                      ,pc15_data 
                                      ,pc15_hora 
                                      ,pc15_opcao 
                       )
                values (
                                $this->pc15_codigo 
                               ,$this->pc15_numsol 
                               ,$this->pc15_codproc 
                               ,$this->pc15_codliclicita 
                               ,$this->pc15_solicitem 
                               ,$this->pc15_quant 
                               ,$this->pc15_vlrun 
                               ,$this->pc15_id_usuario 
                               ,".($this->pc15_data == "null" || $this->pc15_data == ""?"null":"'".$this->pc15_data."'")." 
                               ,'$this->pc15_hora' 
                               ,$this->pc15_opcao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Log das solicitações de compras ($this->pc15_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Log das solicitações de compras já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Log das solicitações de compras ($this->pc15_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc15_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc15_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9652,'$this->pc15_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1662,9652,'','".AddSlashes(pg_result($resaco,0,'pc15_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1662,9653,'','".AddSlashes(pg_result($resaco,0,'pc15_numsol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1662,9654,'','".AddSlashes(pg_result($resaco,0,'pc15_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1662,9655,'','".AddSlashes(pg_result($resaco,0,'pc15_codliclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1662,9656,'','".AddSlashes(pg_result($resaco,0,'pc15_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1662,9657,'','".AddSlashes(pg_result($resaco,0,'pc15_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1662,9658,'','".AddSlashes(pg_result($resaco,0,'pc15_vlrun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1662,9659,'','".AddSlashes(pg_result($resaco,0,'pc15_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1662,9660,'','".AddSlashes(pg_result($resaco,0,'pc15_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1662,9661,'','".AddSlashes(pg_result($resaco,0,'pc15_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1662,9662,'','".AddSlashes(pg_result($resaco,0,'pc15_opcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc15_codigo=null) { 
      $this->atualizacampos();
     $sql = " update solicitalog set ";
     $virgula = "";
     if(trim($this->pc15_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc15_codigo"])){ 
       $sql  .= $virgula." pc15_codigo = $this->pc15_codigo ";
       $virgula = ",";
       if(trim($this->pc15_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "pc15_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc15_numsol)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc15_numsol"])){ 
       $sql  .= $virgula." pc15_numsol = $this->pc15_numsol ";
       $virgula = ",";
       if(trim($this->pc15_numsol) == null ){ 
         $this->erro_sql = " Campo Solicitação nao Informado.";
         $this->erro_campo = "pc15_numsol";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc15_codproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc15_codproc"])){ 
       $sql  .= $virgula." pc15_codproc = $this->pc15_codproc ";
       $virgula = ",";
       if(trim($this->pc15_codproc) == null ){ 
         $this->erro_sql = " Campo Processo de compras nao Informado.";
         $this->erro_campo = "pc15_codproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc15_codliclicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc15_codliclicita"])){ 
        if(trim($this->pc15_codliclicita)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc15_codliclicita"])){ 
           $this->pc15_codliclicita = "0" ; 
        } 
       $sql  .= $virgula." pc15_codliclicita = $this->pc15_codliclicita ";
       $virgula = ",";
     }
     if(trim($this->pc15_solicitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc15_solicitem"])){ 
       $sql  .= $virgula." pc15_solicitem = $this->pc15_solicitem ";
       $virgula = ",";
       if(trim($this->pc15_solicitem) == null ){ 
         $this->erro_sql = " Campo Item nao Informado.";
         $this->erro_campo = "pc15_solicitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc15_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc15_quant"])){ 
       $sql  .= $virgula." pc15_quant = $this->pc15_quant ";
       $virgula = ",";
       if(trim($this->pc15_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "pc15_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc15_vlrun)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc15_vlrun"])){ 
       $sql  .= $virgula." pc15_vlrun = $this->pc15_vlrun ";
       $virgula = ",";
       if(trim($this->pc15_vlrun) == null ){ 
         $this->erro_sql = " Campo Valor unitário nao Informado.";
         $this->erro_campo = "pc15_vlrun";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc15_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc15_id_usuario"])){ 
       $sql  .= $virgula." pc15_id_usuario = $this->pc15_id_usuario ";
       $virgula = ",";
       if(trim($this->pc15_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "pc15_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc15_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc15_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc15_data_dia"] !="") ){ 
       $sql  .= $virgula." pc15_data = '$this->pc15_data' ";
       $virgula = ",";
       if(trim($this->pc15_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "pc15_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc15_data_dia"])){ 
         $sql  .= $virgula." pc15_data = null ";
         $virgula = ",";
         if(trim($this->pc15_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "pc15_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc15_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc15_hora"])){ 
       $sql  .= $virgula." pc15_hora = '$this->pc15_hora' ";
       $virgula = ",";
       if(trim($this->pc15_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "pc15_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc15_opcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc15_opcao"])){ 
       $sql  .= $virgula." pc15_opcao = $this->pc15_opcao ";
       $virgula = ",";
       if(trim($this->pc15_opcao) == null ){ 
         $this->erro_sql = " Campo Opção nao Informado.";
         $this->erro_campo = "pc15_opcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc15_codigo!=null){
       $sql .= " pc15_codigo = $this->pc15_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc15_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9652,'$this->pc15_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc15_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1662,9652,'".AddSlashes(pg_result($resaco,$conresaco,'pc15_codigo'))."','$this->pc15_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc15_numsol"]))
           $resac = db_query("insert into db_acount values($acount,1662,9653,'".AddSlashes(pg_result($resaco,$conresaco,'pc15_numsol'))."','$this->pc15_numsol',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc15_codproc"]))
           $resac = db_query("insert into db_acount values($acount,1662,9654,'".AddSlashes(pg_result($resaco,$conresaco,'pc15_codproc'))."','$this->pc15_codproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc15_codliclicita"]))
           $resac = db_query("insert into db_acount values($acount,1662,9655,'".AddSlashes(pg_result($resaco,$conresaco,'pc15_codliclicita'))."','$this->pc15_codliclicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc15_solicitem"]))
           $resac = db_query("insert into db_acount values($acount,1662,9656,'".AddSlashes(pg_result($resaco,$conresaco,'pc15_solicitem'))."','$this->pc15_solicitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc15_quant"]))
           $resac = db_query("insert into db_acount values($acount,1662,9657,'".AddSlashes(pg_result($resaco,$conresaco,'pc15_quant'))."','$this->pc15_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc15_vlrun"]))
           $resac = db_query("insert into db_acount values($acount,1662,9658,'".AddSlashes(pg_result($resaco,$conresaco,'pc15_vlrun'))."','$this->pc15_vlrun',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc15_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1662,9659,'".AddSlashes(pg_result($resaco,$conresaco,'pc15_id_usuario'))."','$this->pc15_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc15_data"]))
           $resac = db_query("insert into db_acount values($acount,1662,9660,'".AddSlashes(pg_result($resaco,$conresaco,'pc15_data'))."','$this->pc15_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc15_hora"]))
           $resac = db_query("insert into db_acount values($acount,1662,9661,'".AddSlashes(pg_result($resaco,$conresaco,'pc15_hora'))."','$this->pc15_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc15_opcao"]))
           $resac = db_query("insert into db_acount values($acount,1662,9662,'".AddSlashes(pg_result($resaco,$conresaco,'pc15_opcao'))."','$this->pc15_opcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Log das solicitações de compras nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc15_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Log das solicitações de compras nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc15_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc15_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9652,'$pc15_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1662,9652,'','".AddSlashes(pg_result($resaco,$iresaco,'pc15_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1662,9653,'','".AddSlashes(pg_result($resaco,$iresaco,'pc15_numsol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1662,9654,'','".AddSlashes(pg_result($resaco,$iresaco,'pc15_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1662,9655,'','".AddSlashes(pg_result($resaco,$iresaco,'pc15_codliclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1662,9656,'','".AddSlashes(pg_result($resaco,$iresaco,'pc15_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1662,9657,'','".AddSlashes(pg_result($resaco,$iresaco,'pc15_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1662,9658,'','".AddSlashes(pg_result($resaco,$iresaco,'pc15_vlrun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1662,9659,'','".AddSlashes(pg_result($resaco,$iresaco,'pc15_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1662,9660,'','".AddSlashes(pg_result($resaco,$iresaco,'pc15_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1662,9661,'','".AddSlashes(pg_result($resaco,$iresaco,'pc15_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1662,9662,'','".AddSlashes(pg_result($resaco,$iresaco,'pc15_opcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from solicitalog
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc15_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc15_codigo = $pc15_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Log das solicitações de compras nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc15_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Log das solicitações de compras nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc15_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:solicitalog";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc15_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solicitalog ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc15_codigo!=null ){
         $sql2 .= " where solicitalog.pc15_codigo = $pc15_codigo "; 
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
   function sql_query_file ( $pc15_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solicitalog ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc15_codigo!=null ){
         $sql2 .= " where solicitalog.pc15_codigo = $pc15_codigo "; 
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