<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: prefeitura........14/08/06
//CLASSE DA ENTIDADE db_daitomador
class cl_db_daitomador { 
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
   var $w08_sequencial = 0; 
   var $w08_dai = 0; 
   var $w08_mes = 0; 
   var $w08_aliquota = 0; 
   var $w08_valreceita = 0; 
   var $w08_imposto = 0; 
   var $w08_nota = null; 
   var $w08_serie = null; 
   var $w08_servico = null; 
   var $w08_cnpj = null; 
   var $w08_nome = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 w08_sequencial = int4 = Sequencial 
                 w08_dai = int4 = Código do dae 
                 w08_mes = int4 = Mes 
                 w08_aliquota = float8 = Aliquota 
                 w08_valreceita = float8 = Valor da receita 
                 w08_imposto = float8 = Imposto 
                 w08_nota = varchar(10) = Nota 
                 w08_serie = varchar(10) = Série 
                 w08_servico = varchar(40) = Serviço 
                 w08_cnpj = varchar(14) = CNPJ ou CPF 
                 w08_nome = varchar(60) = Nome ou Razão Social 
                 ";
   //funcao construtor da classe 
   function cl_db_daitomador() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_daitomador"); 
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
       $this->w08_sequencial = ($this->w08_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["w08_sequencial"]:$this->w08_sequencial);
       $this->w08_dai = ($this->w08_dai == ""?@$GLOBALS["HTTP_POST_VARS"]["w08_dai"]:$this->w08_dai);
       $this->w08_mes = ($this->w08_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["w08_mes"]:$this->w08_mes);
       $this->w08_aliquota = ($this->w08_aliquota == ""?@$GLOBALS["HTTP_POST_VARS"]["w08_aliquota"]:$this->w08_aliquota);
       $this->w08_valreceita = ($this->w08_valreceita == ""?@$GLOBALS["HTTP_POST_VARS"]["w08_valreceita"]:$this->w08_valreceita);
       $this->w08_imposto = ($this->w08_imposto == ""?@$GLOBALS["HTTP_POST_VARS"]["w08_imposto"]:$this->w08_imposto);
       $this->w08_nota = ($this->w08_nota == ""?@$GLOBALS["HTTP_POST_VARS"]["w08_nota"]:$this->w08_nota);
       $this->w08_serie = ($this->w08_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["w08_serie"]:$this->w08_serie);
       $this->w08_servico = ($this->w08_servico == ""?@$GLOBALS["HTTP_POST_VARS"]["w08_servico"]:$this->w08_servico);
       $this->w08_cnpj = ($this->w08_cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["w08_cnpj"]:$this->w08_cnpj);
       $this->w08_nome = ($this->w08_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["w08_nome"]:$this->w08_nome);
     }else{
       $this->w08_sequencial = ($this->w08_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["w08_sequencial"]:$this->w08_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($w08_sequencial){ 
      $this->atualizacampos();
     if($this->w08_dai == null ){ 
       $this->erro_sql = " Campo Código do dae nao Informado.";
       $this->erro_campo = "w08_dai";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w08_mes == null ){ 
       $this->erro_sql = " Campo Mes nao Informado.";
       $this->erro_campo = "w08_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w08_aliquota == null ){ 
       $this->erro_sql = " Campo Aliquota nao Informado.";
       $this->erro_campo = "w08_aliquota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w08_valreceita == null ){ 
       $this->erro_sql = " Campo Valor da receita nao Informado.";
       $this->erro_campo = "w08_valreceita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w08_imposto == null ){ 
       $this->erro_sql = " Campo Imposto nao Informado.";
       $this->erro_campo = "w08_imposto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w08_nota == null ){ 
       $this->erro_sql = " Campo Nota nao Informado.";
       $this->erro_campo = "w08_nota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w08_servico == null ){ 
       $this->erro_sql = " Campo Serviço nao Informado.";
       $this->erro_campo = "w08_servico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w08_cnpj == null ){ 
       $this->erro_sql = " Campo CNPJ ou CPF nao Informado.";
       $this->erro_campo = "w08_cnpj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w08_nome == null ){ 
       $this->erro_sql = " Campo Nome ou Razão Social nao Informado.";
       $this->erro_campo = "w08_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($w08_sequencial == "" || $w08_sequencial == null ){
       $result = @db_query("select nextval('db_daitomador_w08_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_daitomador_w08_sequencial_seq do campo: w08_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->w08_sequencial = pg_result($result,0,0); 
     }else{
       $result = @db_query("select last_value from db_daitomador_w08_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $w08_sequencial)){
         $this->erro_sql = " Campo w08_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->w08_sequencial = $w08_sequencial; 
       }
     }
     if(($this->w08_sequencial == null) || ($this->w08_sequencial == "") ){ 
       $this->erro_sql = " Campo w08_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_daitomador(
                                       w08_sequencial 
                                      ,w08_dai 
                                      ,w08_mes 
                                      ,w08_aliquota 
                                      ,w08_valreceita 
                                      ,w08_imposto 
                                      ,w08_nota 
                                      ,w08_serie 
                                      ,w08_servico 
                                      ,w08_cnpj 
                                      ,w08_nome 
                       )
                values (
                                $this->w08_sequencial 
                               ,$this->w08_dai 
                               ,$this->w08_mes 
                               ,$this->w08_aliquota 
                               ,$this->w08_valreceita 
                               ,$this->w08_imposto 
                               ,'$this->w08_nota' 
                               ,'$this->w08_serie' 
                               ,'$this->w08_servico' 
                               ,'$this->w08_cnpj' 
                               ,'$this->w08_nome' 
                      )";
     $result = @db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados das retencoes efetuadas ($this->w08_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados das retencoes efetuadas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados das retencoes efetuadas ($this->w08_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w08_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->w08_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountkey values($acount,9114,'$this->w08_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1560,9114,'','".AddSlashes(pg_result($resaco,0,'w08_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1560,9115,'','".AddSlashes(pg_result($resaco,0,'w08_dai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1560,9116,'','".AddSlashes(pg_result($resaco,0,'w08_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1560,9117,'','".AddSlashes(pg_result($resaco,0,'w08_aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1560,9118,'','".AddSlashes(pg_result($resaco,0,'w08_valreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1560,9119,'','".AddSlashes(pg_result($resaco,0,'w08_imposto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1560,9170,'','".AddSlashes(pg_result($resaco,0,'w08_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1560,9171,'','".AddSlashes(pg_result($resaco,0,'w08_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1560,9174,'','".AddSlashes(pg_result($resaco,0,'w08_servico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1560,9172,'','".AddSlashes(pg_result($resaco,0,'w08_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1560,9173,'','".AddSlashes(pg_result($resaco,0,'w08_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($w08_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_daitomador set ";
     $virgula = "";
     if(trim($this->w08_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w08_sequencial"])){ 
       $sql  .= $virgula." w08_sequencial = $this->w08_sequencial ";
       $virgula = ",";
       if(trim($this->w08_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "w08_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w08_dai)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w08_dai"])){ 
       $sql  .= $virgula." w08_dai = $this->w08_dai ";
       $virgula = ",";
       if(trim($this->w08_dai) == null ){ 
         $this->erro_sql = " Campo Código do dae nao Informado.";
         $this->erro_campo = "w08_dai";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w08_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w08_mes"])){ 
       $sql  .= $virgula." w08_mes = $this->w08_mes ";
       $virgula = ",";
       if(trim($this->w08_mes) == null ){ 
         $this->erro_sql = " Campo Mes nao Informado.";
         $this->erro_campo = "w08_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w08_aliquota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w08_aliquota"])){ 
       $sql  .= $virgula." w08_aliquota = $this->w08_aliquota ";
       $virgula = ",";
       if(trim($this->w08_aliquota) == null ){ 
         $this->erro_sql = " Campo Aliquota nao Informado.";
         $this->erro_campo = "w08_aliquota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w08_valreceita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w08_valreceita"])){ 
       $sql  .= $virgula." w08_valreceita = $this->w08_valreceita ";
       $virgula = ",";
       if(trim($this->w08_valreceita) == null ){ 
         $this->erro_sql = " Campo Valor da receita nao Informado.";
         $this->erro_campo = "w08_valreceita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w08_imposto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w08_imposto"])){ 
       $sql  .= $virgula." w08_imposto = $this->w08_imposto ";
       $virgula = ",";
       if(trim($this->w08_imposto) == null ){ 
         $this->erro_sql = " Campo Imposto nao Informado.";
         $this->erro_campo = "w08_imposto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w08_nota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w08_nota"])){ 
       $sql  .= $virgula." w08_nota = '$this->w08_nota' ";
       $virgula = ",";
       if(trim($this->w08_nota) == null ){ 
         $this->erro_sql = " Campo Nota nao Informado.";
         $this->erro_campo = "w08_nota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w08_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w08_serie"])){ 
       $sql  .= $virgula." w08_serie = '$this->w08_serie' ";
       $virgula = ",";
     }
     if(trim($this->w08_servico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w08_servico"])){ 
       $sql  .= $virgula." w08_servico = '$this->w08_servico' ";
       $virgula = ",";
       if(trim($this->w08_servico) == null ){ 
         $this->erro_sql = " Campo Serviço nao Informado.";
         $this->erro_campo = "w08_servico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w08_cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w08_cnpj"])){ 
       $sql  .= $virgula." w08_cnpj = '$this->w08_cnpj' ";
       $virgula = ",";
       if(trim($this->w08_cnpj) == null ){ 
         $this->erro_sql = " Campo CNPJ ou CPF nao Informado.";
         $this->erro_campo = "w08_cnpj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w08_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w08_nome"])){ 
       $sql  .= $virgula." w08_nome = '$this->w08_nome' ";
       $virgula = ",";
       if(trim($this->w08_nome) == null ){ 
         $this->erro_sql = " Campo Nome ou Razão Social nao Informado.";
         $this->erro_campo = "w08_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($w08_sequencial!=null){
       $sql .= " w08_sequencial = $this->w08_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->w08_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountkey values($acount,9114,'$this->w08_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w08_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1560,9114,'".AddSlashes(pg_result($resaco,$conresaco,'w08_sequencial'))."','$this->w08_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w08_dai"]))
           $resac = db_query("insert into db_acount values($acount,1560,9115,'".AddSlashes(pg_result($resaco,$conresaco,'w08_dai'))."','$this->w08_dai',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w08_mes"]))
           $resac = db_query("insert into db_acount values($acount,1560,9116,'".AddSlashes(pg_result($resaco,$conresaco,'w08_mes'))."','$this->w08_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w08_aliquota"]))
           $resac = db_query("insert into db_acount values($acount,1560,9117,'".AddSlashes(pg_result($resaco,$conresaco,'w08_aliquota'))."','$this->w08_aliquota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w08_valreceita"]))
           $resac = db_query("insert into db_acount values($acount,1560,9118,'".AddSlashes(pg_result($resaco,$conresaco,'w08_valreceita'))."','$this->w08_valreceita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w08_imposto"]))
           $resac = db_query("insert into db_acount values($acount,1560,9119,'".AddSlashes(pg_result($resaco,$conresaco,'w08_imposto'))."','$this->w08_imposto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w08_nota"]))
           $resac = db_query("insert into db_acount values($acount,1560,9170,'".AddSlashes(pg_result($resaco,$conresaco,'w08_nota'))."','$this->w08_nota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w08_serie"]))
           $resac = db_query("insert into db_acount values($acount,1560,9171,'".AddSlashes(pg_result($resaco,$conresaco,'w08_serie'))."','$this->w08_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w08_servico"]))
           $resac = db_query("insert into db_acount values($acount,1560,9174,'".AddSlashes(pg_result($resaco,$conresaco,'w08_servico'))."','$this->w08_servico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w08_cnpj"]))
           $resac = db_query("insert into db_acount values($acount,1560,9172,'".AddSlashes(pg_result($resaco,$conresaco,'w08_cnpj'))."','$this->w08_cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w08_nome"]))
           $resac = db_query("insert into db_acount values($acount,1560,9173,'".AddSlashes(pg_result($resaco,$conresaco,'w08_nome'))."','$this->w08_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados das retencoes efetuadas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->w08_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados das retencoes efetuadas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->w08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($w08_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($w08_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountkey values($acount,9114,'$w08_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1560,9114,'','".AddSlashes(pg_result($resaco,$iresaco,'w08_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1560,9115,'','".AddSlashes(pg_result($resaco,$iresaco,'w08_dai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1560,9116,'','".AddSlashes(pg_result($resaco,$iresaco,'w08_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1560,9117,'','".AddSlashes(pg_result($resaco,$iresaco,'w08_aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1560,9118,'','".AddSlashes(pg_result($resaco,$iresaco,'w08_valreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1560,9119,'','".AddSlashes(pg_result($resaco,$iresaco,'w08_imposto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1560,9170,'','".AddSlashes(pg_result($resaco,$iresaco,'w08_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1560,9171,'','".AddSlashes(pg_result($resaco,$iresaco,'w08_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1560,9174,'','".AddSlashes(pg_result($resaco,$iresaco,'w08_servico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1560,9172,'','".AddSlashes(pg_result($resaco,$iresaco,'w08_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1560,9173,'','".AddSlashes(pg_result($resaco,$iresaco,'w08_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_daitomador
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($w08_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " w08_sequencial = $w08_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados das retencoes efetuadas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$w08_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados das retencoes efetuadas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$w08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$w08_sequencial;
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
     $result = @db_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:db_daitomador";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $w08_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_daitomador ";
     $sql .= "      inner join db_dae  on  db_dae.w04_codigo = db_daitomador.w08_dai";
     $sql2 = "";
     if($dbwhere==""){
       if($w08_sequencial!=null ){
         $sql2 .= " where db_daitomador.w08_sequencial = $w08_sequencial "; 
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
  
  function sql_query_paga ( $w08_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_daitomador ";
     $sql .= "      inner join db_dae  on  db_dae.w04_codigo = db_daitomador.w08_dai";
     $sql .= "      left  join db_daitomadorpaga on db_daitomadorpaga.w09_daitomador = db_daitomador.w08_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($w08_sequencial!=null ){
         $sql2 .= " where db_daitomador.w08_sequencial = $w08_sequencial "; 
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
   function sql_query_file ( $w08_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_daitomador ";
     $sql2 = "";
     if($dbwhere==""){
       if($w08_sequencial!=null ){
         $sql2 .= " where db_daitomador.w08_sequencial = $w08_sequencial "; 
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