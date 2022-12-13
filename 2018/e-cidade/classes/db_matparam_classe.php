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

//MODULO: material
//CLASSE DA ENTIDADE matparam
class cl_matparam { 
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
   var $m90_tipocontrol = null; 
   var $m90_reqsemest = 'f'; 
   var $m90_dtimplan_dia = null; 
   var $m90_dtimplan_mes = null; 
   var $m90_dtimplan_ano = null; 
   var $m90_dtimplan = null; 
   var $m90_deptalmox = 'f'; 
   var $m90_liqentoc = 'f'; 
   var $m90_entratrans = 'f'; 
   var $m90_modrelsaidamat = 0; 
   var $m90_almoxordemcompra = 0; 
   var $m90_prazovenc = 0; 
   var $m90_corfundorequisicao = 0; 
   var $m90_mostrarsaldosolictransf = 0; 
   var $m90_validarsaldosolictransf = 0; 
   var $m90_versaldoitemreq = 'f'; 
   var $m90_db_estrutura = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m90_tipocontrol = char(1) = Tipo de Controle do Estoque 
                 m90_reqsemest = bool = Fazer Requisição sem Estoque 
                 m90_dtimplan = date = Data Implantação 
                 m90_deptalmox = bool = Permite Depart. p/ mais de 1 Almox 
                 m90_liqentoc = bool = Gerar nota de liquidação automaticamente 
                 m90_entratrans = bool = Entrada com Saida/Transferência 
                 m90_modrelsaidamat = int4 = Modelo do Relatorio de  Saída de Materiais 
                 m90_almoxordemcompra = int4 = Almoxarifado da Ordem de Compra 
                 m90_prazovenc = int4 = Prazo à Vencer em Dias 
                 m90_corfundorequisicao = int4 = Cor de Fundo da Requisição 
                 m90_mostrarsaldosolictransf = int4 = Mostrar saldo mat. na solic. de transf. 
                 m90_validarsaldosolictransf = int4 = Validar qtd. informada com saldo 
                 m90_versaldoitemreq = bool = Visualizar Saldo do Item  na Requisição 
                 m90_db_estrutura = int4 = Estrutura dos Grupos 
                 ";
   //funcao construtor da classe 
   function cl_matparam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matparam"); 
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
       $this->m90_tipocontrol = ($this->m90_tipocontrol == ""?@$GLOBALS["HTTP_POST_VARS"]["m90_tipocontrol"]:$this->m90_tipocontrol);
       $this->m90_reqsemest = ($this->m90_reqsemest == "f"?@$GLOBALS["HTTP_POST_VARS"]["m90_reqsemest"]:$this->m90_reqsemest);
       if($this->m90_dtimplan == ""){
         $this->m90_dtimplan_dia = ($this->m90_dtimplan_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m90_dtimplan_dia"]:$this->m90_dtimplan_dia);
         $this->m90_dtimplan_mes = ($this->m90_dtimplan_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m90_dtimplan_mes"]:$this->m90_dtimplan_mes);
         $this->m90_dtimplan_ano = ($this->m90_dtimplan_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m90_dtimplan_ano"]:$this->m90_dtimplan_ano);
         if($this->m90_dtimplan_dia != ""){
            $this->m90_dtimplan = $this->m90_dtimplan_ano."-".$this->m90_dtimplan_mes."-".$this->m90_dtimplan_dia;
         }
       }
       $this->m90_deptalmox = ($this->m90_deptalmox == "f"?@$GLOBALS["HTTP_POST_VARS"]["m90_deptalmox"]:$this->m90_deptalmox);
       $this->m90_liqentoc = ($this->m90_liqentoc == "f"?@$GLOBALS["HTTP_POST_VARS"]["m90_liqentoc"]:$this->m90_liqentoc);
       $this->m90_entratrans = ($this->m90_entratrans == "f"?@$GLOBALS["HTTP_POST_VARS"]["m90_entratrans"]:$this->m90_entratrans);
       $this->m90_modrelsaidamat = ($this->m90_modrelsaidamat == ""?@$GLOBALS["HTTP_POST_VARS"]["m90_modrelsaidamat"]:$this->m90_modrelsaidamat);
       $this->m90_almoxordemcompra = ($this->m90_almoxordemcompra == ""?@$GLOBALS["HTTP_POST_VARS"]["m90_almoxordemcompra"]:$this->m90_almoxordemcompra);
       $this->m90_prazovenc = ($this->m90_prazovenc == ""?@$GLOBALS["HTTP_POST_VARS"]["m90_prazovenc"]:$this->m90_prazovenc);
       $this->m90_corfundorequisicao = ($this->m90_corfundorequisicao == ""?@$GLOBALS["HTTP_POST_VARS"]["m90_corfundorequisicao"]:$this->m90_corfundorequisicao);
       $this->m90_mostrarsaldosolictransf = ($this->m90_mostrarsaldosolictransf == ""?@$GLOBALS["HTTP_POST_VARS"]["m90_mostrarsaldosolictransf"]:$this->m90_mostrarsaldosolictransf);
       $this->m90_validarsaldosolictransf = ($this->m90_validarsaldosolictransf == ""?@$GLOBALS["HTTP_POST_VARS"]["m90_validarsaldosolictransf"]:$this->m90_validarsaldosolictransf);
       $this->m90_versaldoitemreq = ($this->m90_versaldoitemreq == "f"?@$GLOBALS["HTTP_POST_VARS"]["m90_versaldoitemreq"]:$this->m90_versaldoitemreq);
       $this->m90_db_estrutura = ($this->m90_db_estrutura == ""?@$GLOBALS["HTTP_POST_VARS"]["m90_db_estrutura"]:$this->m90_db_estrutura);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->m90_tipocontrol == null ){ 
       $this->erro_sql = " Campo Tipo de Controle do Estoque nao Informado.";
       $this->erro_campo = "m90_tipocontrol";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m90_reqsemest == null ){ 
       $this->erro_sql = " Campo Fazer Requisição sem Estoque nao Informado.";
       $this->erro_campo = "m90_reqsemest";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m90_dtimplan == null ){ 
       $this->erro_sql = " Campo Data Implantação nao Informado.";
       $this->erro_campo = "m90_dtimplan_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m90_deptalmox == null ){ 
       $this->erro_sql = " Campo Permite Depart. p/ mais de 1 Almox nao Informado.";
       $this->erro_campo = "m90_deptalmox";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m90_liqentoc == null ){ 
       $this->erro_sql = " Campo Gerar nota de liquidação automaticamente nao Informado.";
       $this->erro_campo = "m90_liqentoc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m90_entratrans == null ){ 
       $this->erro_sql = " Campo Entrada com Saida/Transferência nao Informado.";
       $this->erro_campo = "m90_entratrans";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m90_modrelsaidamat == null ){ 
       $this->m90_modrelsaidamat = "0";
     }
     if($this->m90_almoxordemcompra == null ){ 
       $this->erro_sql = " Campo Almoxarifado da Ordem de Compra nao Informado.";
       $this->erro_campo = "m90_almoxordemcompra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m90_prazovenc == null ){ 
       $this->erro_sql = " Campo Prazo à Vencer em Dias nao Informado.";
       $this->erro_campo = "m90_prazovenc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m90_corfundorequisicao == null ){ 
       $this->erro_sql = " Campo Cor de Fundo da Requisição nao Informado.";
       $this->erro_campo = "m90_corfundorequisicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m90_mostrarsaldosolictransf == null ){ 
       $this->erro_sql = " Campo Mostrar saldo mat. na solic. de transf. nao Informado.";
       $this->erro_campo = "m90_mostrarsaldosolictransf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m90_validarsaldosolictransf == null ){ 
       $this->erro_sql = " Campo Validar qtd. informada com saldo nao Informado.";
       $this->erro_campo = "m90_validarsaldosolictransf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m90_versaldoitemreq == null ){ 
       $this->erro_sql = " Campo Visualizar Saldo do Item  na Requisição nao Informado.";
       $this->erro_campo = "m90_versaldoitemreq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m90_db_estrutura == null ){ 
       $this->erro_sql = " Campo Estrutura dos Grupos nao Informado.";
       $this->erro_campo = "m90_db_estrutura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matparam(
                                       m90_tipocontrol 
                                      ,m90_reqsemest 
                                      ,m90_dtimplan 
                                      ,m90_deptalmox 
                                      ,m90_liqentoc 
                                      ,m90_entratrans 
                                      ,m90_modrelsaidamat 
                                      ,m90_almoxordemcompra 
                                      ,m90_prazovenc 
                                      ,m90_corfundorequisicao 
                                      ,m90_mostrarsaldosolictransf 
                                      ,m90_validarsaldosolictransf 
                                      ,m90_versaldoitemreq 
                                      ,m90_db_estrutura 
                       )
                values (
                                '$this->m90_tipocontrol' 
                               ,'$this->m90_reqsemest' 
                               ,".($this->m90_dtimplan == "null" || $this->m90_dtimplan == ""?"null":"'".$this->m90_dtimplan."'")." 
                               ,'$this->m90_deptalmox' 
                               ,'$this->m90_liqentoc' 
                               ,'$this->m90_entratrans' 
                               ,$this->m90_modrelsaidamat 
                               ,$this->m90_almoxordemcompra 
                               ,$this->m90_prazovenc 
                               ,$this->m90_corfundorequisicao 
                               ,$this->m90_mostrarsaldosolictransf 
                               ,$this->m90_validarsaldosolictransf 
                               ,'$this->m90_versaldoitemreq' 
                               ,$this->m90_db_estrutura 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "matparam () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "matparam já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "matparam () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update matparam set ";
     $virgula = "";
     if(trim($this->m90_tipocontrol)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m90_tipocontrol"])){ 
       $sql  .= $virgula." m90_tipocontrol = '$this->m90_tipocontrol' ";
       $virgula = ",";
       if(trim($this->m90_tipocontrol) == null ){ 
         $this->erro_sql = " Campo Tipo de Controle do Estoque nao Informado.";
         $this->erro_campo = "m90_tipocontrol";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m90_reqsemest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m90_reqsemest"])){ 
       $sql  .= $virgula." m90_reqsemest = '$this->m90_reqsemest' ";
       $virgula = ",";
       if(trim($this->m90_reqsemest) == null ){ 
         $this->erro_sql = " Campo Fazer Requisição sem Estoque nao Informado.";
         $this->erro_campo = "m90_reqsemest";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m90_dtimplan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m90_dtimplan_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m90_dtimplan_dia"] !="") ){ 
       $sql  .= $virgula." m90_dtimplan = '$this->m90_dtimplan' ";
       $virgula = ",";
       if(trim($this->m90_dtimplan) == null ){ 
         $this->erro_sql = " Campo Data Implantação nao Informado.";
         $this->erro_campo = "m90_dtimplan_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["m90_dtimplan_dia"])){ 
         $sql  .= $virgula." m90_dtimplan = null ";
         $virgula = ",";
         if(trim($this->m90_dtimplan) == null ){ 
           $this->erro_sql = " Campo Data Implantação nao Informado.";
           $this->erro_campo = "m90_dtimplan_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->m90_deptalmox)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m90_deptalmox"])){ 
       $sql  .= $virgula." m90_deptalmox = '$this->m90_deptalmox' ";
       $virgula = ",";
       if(trim($this->m90_deptalmox) == null ){ 
         $this->erro_sql = " Campo Permite Depart. p/ mais de 1 Almox nao Informado.";
         $this->erro_campo = "m90_deptalmox";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m90_liqentoc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m90_liqentoc"])){ 
       $sql  .= $virgula." m90_liqentoc = '$this->m90_liqentoc' ";
       $virgula = ",";
       if(trim($this->m90_liqentoc) == null ){ 
         $this->erro_sql = " Campo Gerar nota de liquidação automaticamente nao Informado.";
         $this->erro_campo = "m90_liqentoc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m90_entratrans)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m90_entratrans"])){ 
       $sql  .= $virgula." m90_entratrans = '$this->m90_entratrans' ";
       $virgula = ",";
       if(trim($this->m90_entratrans) == null ){ 
         $this->erro_sql = " Campo Entrada com Saida/Transferência nao Informado.";
         $this->erro_campo = "m90_entratrans";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m90_modrelsaidamat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m90_modrelsaidamat"])){ 
        if(trim($this->m90_modrelsaidamat)=="" && isset($GLOBALS["HTTP_POST_VARS"]["m90_modrelsaidamat"])){ 
           $this->m90_modrelsaidamat = "0" ; 
        } 
       $sql  .= $virgula." m90_modrelsaidamat = $this->m90_modrelsaidamat ";
       $virgula = ",";
     }
     if(trim($this->m90_almoxordemcompra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m90_almoxordemcompra"])){ 
       $sql  .= $virgula." m90_almoxordemcompra = $this->m90_almoxordemcompra ";
       $virgula = ",";
       if(trim($this->m90_almoxordemcompra) == null ){ 
         $this->erro_sql = " Campo Almoxarifado da Ordem de Compra nao Informado.";
         $this->erro_campo = "m90_almoxordemcompra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m90_prazovenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m90_prazovenc"])){ 
       $sql  .= $virgula." m90_prazovenc = $this->m90_prazovenc ";
       $virgula = ",";
       if(trim($this->m90_prazovenc) == null ){ 
         $this->erro_sql = " Campo Prazo à Vencer em Dias nao Informado.";
         $this->erro_campo = "m90_prazovenc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m90_corfundorequisicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m90_corfundorequisicao"])){ 
       $sql  .= $virgula." m90_corfundorequisicao = $this->m90_corfundorequisicao ";
       $virgula = ",";
       if(trim($this->m90_corfundorequisicao) == null ){ 
         $this->erro_sql = " Campo Cor de Fundo da Requisição nao Informado.";
         $this->erro_campo = "m90_corfundorequisicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m90_mostrarsaldosolictransf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m90_mostrarsaldosolictransf"])){ 
       $sql  .= $virgula." m90_mostrarsaldosolictransf = $this->m90_mostrarsaldosolictransf ";
       $virgula = ",";
       if(trim($this->m90_mostrarsaldosolictransf) == null ){ 
         $this->erro_sql = " Campo Mostrar saldo mat. na solic. de transf. nao Informado.";
         $this->erro_campo = "m90_mostrarsaldosolictransf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m90_validarsaldosolictransf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m90_validarsaldosolictransf"])){ 
       $sql  .= $virgula." m90_validarsaldosolictransf = $this->m90_validarsaldosolictransf ";
       $virgula = ",";
       if(trim($this->m90_validarsaldosolictransf) == null ){ 
         $this->erro_sql = " Campo Validar qtd. informada com saldo nao Informado.";
         $this->erro_campo = "m90_validarsaldosolictransf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m90_versaldoitemreq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m90_versaldoitemreq"])){ 
       $sql  .= $virgula." m90_versaldoitemreq = '$this->m90_versaldoitemreq' ";
       $virgula = ",";
       if(trim($this->m90_versaldoitemreq) == null ){ 
         $this->erro_sql = " Campo Visualizar Saldo do Item  na Requisição nao Informado.";
         $this->erro_campo = "m90_versaldoitemreq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m90_db_estrutura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m90_db_estrutura"])){ 
       $sql  .= $virgula." m90_db_estrutura = $this->m90_db_estrutura ";
       $virgula = ",";
       if(trim($this->m90_db_estrutura) == null ){ 
         $this->erro_sql = " Campo Estrutura dos Grupos nao Informado.";
         $this->erro_campo = "m90_db_estrutura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
    $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matparam nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matparam nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from matparam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matparam nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matparam nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:matparam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matparam ";
     $sql .= "      left join db_estrutura on m90_db_estrutura =  db77_codestrut";
     $sql2 = "";
     if($dbwhere==""){
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
   function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matparam ";
     $sql2 = "";
     if($dbwhere==""){
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