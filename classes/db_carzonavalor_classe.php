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

//MODULO: cadastro
//CLASSE DA ENTIDADE carzonavalor
class cl_carzonavalor { 
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
   var $j72_sequencial = 0; 
   var $j72_anousu = 0; 
   var $j72_caract = 0; 
   var $j72_zona = 0; 
   var $j72_tipo = null; 
   var $j72_quant = 0; 
   var $j72_ini = 0; 
   var $j72_fim = 0; 
   var $j72_valor = 0; 
   var $j72_quantini = 0; 
   var $j72_quantfim = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j72_sequencial = int4 = Código Sequencial 
                 j72_anousu = int4 = Ano 
                 j72_caract = int8 = Caracteristica 
                 j72_zona = int8 = Zona fiscal 
                 j72_tipo = varchar(1) = Tipo 
                 j72_quant = float8 = Quantidade 
                 j72_ini = float8 = Valor inicial 
                 j72_fim = float8 = Valor final 
                 j72_valor = float8 = Valor 
                 j72_quantini = float8 = Quantidade inicial 
                 j72_quantfim = float8 = Quantidade final 
                 ";
   //funcao construtor da classe 
   function cl_carzonavalor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("carzonavalor"); 
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
       $this->j72_sequencial = ($this->j72_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j72_sequencial"]:$this->j72_sequencial);
       $this->j72_anousu = ($this->j72_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j72_anousu"]:$this->j72_anousu);
       $this->j72_caract = ($this->j72_caract == ""?@$GLOBALS["HTTP_POST_VARS"]["j72_caract"]:$this->j72_caract);
       $this->j72_zona = ($this->j72_zona == ""?@$GLOBALS["HTTP_POST_VARS"]["j72_zona"]:$this->j72_zona);
       $this->j72_tipo = ($this->j72_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["j72_tipo"]:$this->j72_tipo);
       $this->j72_quant = ($this->j72_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["j72_quant"]:$this->j72_quant);
       $this->j72_ini = ($this->j72_ini == ""?@$GLOBALS["HTTP_POST_VARS"]["j72_ini"]:$this->j72_ini);
       $this->j72_fim = ($this->j72_fim == ""?@$GLOBALS["HTTP_POST_VARS"]["j72_fim"]:$this->j72_fim);
       $this->j72_valor = ($this->j72_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["j72_valor"]:$this->j72_valor);
       $this->j72_quantini = ($this->j72_quantini == ""?@$GLOBALS["HTTP_POST_VARS"]["j72_quantini"]:$this->j72_quantini);
       $this->j72_quantfim = ($this->j72_quantfim == ""?@$GLOBALS["HTTP_POST_VARS"]["j72_quantfim"]:$this->j72_quantfim);
     }else{
       $this->j72_sequencial = ($this->j72_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j72_sequencial"]:$this->j72_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j72_sequencial){ 
      $this->atualizacampos();
     if($this->j72_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "j72_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j72_caract == null ){ 
       $this->erro_sql = " Campo Caracteristica nao Informado.";
       $this->erro_campo = "j72_caract";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j72_zona == null ){ 
       $this->erro_sql = " Campo Zona fiscal nao Informado.";
       $this->erro_campo = "j72_zona";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j72_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "j72_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j72_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "j72_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j72_ini == null ){ 
       $this->erro_sql = " Campo Valor inicial nao Informado.";
       $this->erro_campo = "j72_ini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j72_fim == null ){ 
       $this->erro_sql = " Campo Valor final nao Informado.";
       $this->erro_campo = "j72_fim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j72_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "j72_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j72_quantini == null ){ 
       $this->erro_sql = " Campo Quantidade inicial nao Informado.";
       $this->erro_campo = "j72_quantini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j72_quantfim == null ){ 
       $this->erro_sql = " Campo Quantidade final nao Informado.";
       $this->erro_campo = "j72_quantfim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j72_sequencial == "" || $j72_sequencial == null ){
       $result = db_query("select nextval('carzonavalor_j72_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: carzonavalor_j72_sequencial_seq do campo: j72_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j72_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from carzonavalor_j72_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j72_sequencial)){
         $this->erro_sql = " Campo j72_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j72_sequencial = $j72_sequencial; 
       }
     }
     if(($this->j72_sequencial == null) || ($this->j72_sequencial == "") ){ 
       $this->erro_sql = " Campo j72_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into carzonavalor(
                                       j72_sequencial 
                                      ,j72_anousu 
                                      ,j72_caract 
                                      ,j72_zona 
                                      ,j72_tipo 
                                      ,j72_quant 
                                      ,j72_ini 
                                      ,j72_fim 
                                      ,j72_valor 
                                      ,j72_quantini 
                                      ,j72_quantfim 
                       )
                values (
                                $this->j72_sequencial 
                               ,$this->j72_anousu 
                               ,$this->j72_caract 
                               ,$this->j72_zona 
                               ,'$this->j72_tipo' 
                               ,$this->j72_quant 
                               ,$this->j72_ini 
                               ,$this->j72_fim 
                               ,$this->j72_valor 
                               ,$this->j72_quantini 
                               ,$this->j72_quantfim 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores das caracteristicas por zona ($this->j72_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores das caracteristicas por zona já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores das caracteristicas por zona ($this->j72_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j72_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j72_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17493,'$this->j72_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1141,17493,'','".AddSlashes(pg_result($resaco,0,'j72_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1141,6928,'','".AddSlashes(pg_result($resaco,0,'j72_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1141,6929,'','".AddSlashes(pg_result($resaco,0,'j72_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1141,6930,'','".AddSlashes(pg_result($resaco,0,'j72_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1141,6931,'','".AddSlashes(pg_result($resaco,0,'j72_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1141,8072,'','".AddSlashes(pg_result($resaco,0,'j72_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1141,8070,'','".AddSlashes(pg_result($resaco,0,'j72_ini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1141,8071,'','".AddSlashes(pg_result($resaco,0,'j72_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1141,6932,'','".AddSlashes(pg_result($resaco,0,'j72_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1141,9814,'','".AddSlashes(pg_result($resaco,0,'j72_quantini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1141,9815,'','".AddSlashes(pg_result($resaco,0,'j72_quantfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j72_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update carzonavalor set ";
     $virgula = "";
     if(trim($this->j72_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j72_sequencial"])){ 
       $sql  .= $virgula." j72_sequencial = $this->j72_sequencial ";
       $virgula = ",";
       if(trim($this->j72_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "j72_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j72_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j72_anousu"])){ 
       $sql  .= $virgula." j72_anousu = $this->j72_anousu ";
       $virgula = ",";
       if(trim($this->j72_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "j72_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j72_caract)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j72_caract"])){ 
       $sql  .= $virgula." j72_caract = $this->j72_caract ";
       $virgula = ",";
       if(trim($this->j72_caract) == null ){ 
         $this->erro_sql = " Campo Caracteristica nao Informado.";
         $this->erro_campo = "j72_caract";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j72_zona)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j72_zona"])){ 
       $sql  .= $virgula." j72_zona = $this->j72_zona ";
       $virgula = ",";
       if(trim($this->j72_zona) == null ){ 
         $this->erro_sql = " Campo Zona fiscal nao Informado.";
         $this->erro_campo = "j72_zona";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j72_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j72_tipo"])){ 
       $sql  .= $virgula." j72_tipo = '$this->j72_tipo' ";
       $virgula = ",";
       if(trim($this->j72_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "j72_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j72_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j72_quant"])){ 
       $sql  .= $virgula." j72_quant = $this->j72_quant ";
       $virgula = ",";
       if(trim($this->j72_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "j72_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j72_ini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j72_ini"])){ 
       $sql  .= $virgula." j72_ini = $this->j72_ini ";
       $virgula = ",";
       if(trim($this->j72_ini) == null ){ 
         $this->erro_sql = " Campo Valor inicial nao Informado.";
         $this->erro_campo = "j72_ini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j72_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j72_fim"])){ 
       $sql  .= $virgula." j72_fim = $this->j72_fim ";
       $virgula = ",";
       if(trim($this->j72_fim) == null ){ 
         $this->erro_sql = " Campo Valor final nao Informado.";
         $this->erro_campo = "j72_fim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j72_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j72_valor"])){ 
       $sql  .= $virgula." j72_valor = $this->j72_valor ";
       $virgula = ",";
       if(trim($this->j72_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "j72_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j72_quantini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j72_quantini"])){ 
       $sql  .= $virgula." j72_quantini = $this->j72_quantini ";
       $virgula = ",";
       if(trim($this->j72_quantini) == null ){ 
         $this->erro_sql = " Campo Quantidade inicial nao Informado.";
         $this->erro_campo = "j72_quantini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j72_quantfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j72_quantfim"])){ 
       $sql  .= $virgula." j72_quantfim = $this->j72_quantfim ";
       $virgula = ",";
       if(trim($this->j72_quantfim) == null ){ 
         $this->erro_sql = " Campo Quantidade final nao Informado.";
         $this->erro_campo = "j72_quantfim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j72_sequencial!=null){
       $sql .= " j72_sequencial = $this->j72_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j72_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17493,'$this->j72_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j72_sequencial"]) || $this->j72_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,1141,17493,'".AddSlashes(pg_result($resaco,$conresaco,'j72_sequencial'))."','$this->j72_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j72_anousu"]) || $this->j72_anousu != "")
           $resac = db_query("insert into db_acount values($acount,1141,6928,'".AddSlashes(pg_result($resaco,$conresaco,'j72_anousu'))."','$this->j72_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j72_caract"]) || $this->j72_caract != "")
           $resac = db_query("insert into db_acount values($acount,1141,6929,'".AddSlashes(pg_result($resaco,$conresaco,'j72_caract'))."','$this->j72_caract',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j72_zona"]) || $this->j72_zona != "")
           $resac = db_query("insert into db_acount values($acount,1141,6930,'".AddSlashes(pg_result($resaco,$conresaco,'j72_zona'))."','$this->j72_zona',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j72_tipo"]) || $this->j72_tipo != "")
           $resac = db_query("insert into db_acount values($acount,1141,6931,'".AddSlashes(pg_result($resaco,$conresaco,'j72_tipo'))."','$this->j72_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j72_quant"]) || $this->j72_quant != "")
           $resac = db_query("insert into db_acount values($acount,1141,8072,'".AddSlashes(pg_result($resaco,$conresaco,'j72_quant'))."','$this->j72_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j72_ini"]) || $this->j72_ini != "")
           $resac = db_query("insert into db_acount values($acount,1141,8070,'".AddSlashes(pg_result($resaco,$conresaco,'j72_ini'))."','$this->j72_ini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j72_fim"]) || $this->j72_fim != "")
           $resac = db_query("insert into db_acount values($acount,1141,8071,'".AddSlashes(pg_result($resaco,$conresaco,'j72_fim'))."','$this->j72_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j72_valor"]) || $this->j72_valor != "")
           $resac = db_query("insert into db_acount values($acount,1141,6932,'".AddSlashes(pg_result($resaco,$conresaco,'j72_valor'))."','$this->j72_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j72_quantini"]) || $this->j72_quantini != "")
           $resac = db_query("insert into db_acount values($acount,1141,9814,'".AddSlashes(pg_result($resaco,$conresaco,'j72_quantini'))."','$this->j72_quantini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j72_quantfim"]) || $this->j72_quantfim != "")
           $resac = db_query("insert into db_acount values($acount,1141,9815,'".AddSlashes(pg_result($resaco,$conresaco,'j72_quantfim'))."','$this->j72_quantfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores das caracteristicas por zona nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j72_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores das caracteristicas por zona nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j72_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j72_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17493,'$j72_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1141,17493,'','".AddSlashes(pg_result($resaco,$iresaco,'j72_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1141,6928,'','".AddSlashes(pg_result($resaco,$iresaco,'j72_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1141,6929,'','".AddSlashes(pg_result($resaco,$iresaco,'j72_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1141,6930,'','".AddSlashes(pg_result($resaco,$iresaco,'j72_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1141,6931,'','".AddSlashes(pg_result($resaco,$iresaco,'j72_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1141,8072,'','".AddSlashes(pg_result($resaco,$iresaco,'j72_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1141,8070,'','".AddSlashes(pg_result($resaco,$iresaco,'j72_ini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1141,8071,'','".AddSlashes(pg_result($resaco,$iresaco,'j72_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1141,6932,'','".AddSlashes(pg_result($resaco,$iresaco,'j72_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1141,9814,'','".AddSlashes(pg_result($resaco,$iresaco,'j72_quantini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1141,9815,'','".AddSlashes(pg_result($resaco,$iresaco,'j72_quantfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from carzonavalor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j72_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j72_sequencial = $j72_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores das caracteristicas por zona nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j72_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores das caracteristicas por zona nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j72_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:carzonavalor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from carzonavalor ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = carzonavalor.j72_caract";
     $sql .= "      inner join zonas  on  zonas.j50_zona = carzonavalor.j72_zona";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql2 = "";
     if($dbwhere==""){
       if($j72_sequencial!=null ){
         $sql2 .= " where carzonavalor.j72_sequencial = $j72_sequencial "; 
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
   function sql_query_file ( $j72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from carzonavalor ";
     $sql2 = "";
     if($dbwhere==""){
       if($j72_sequencial!=null ){
         $sql2 .= " where carzonavalor.j72_sequencial = $j72_sequencial "; 
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